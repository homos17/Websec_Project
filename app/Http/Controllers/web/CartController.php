<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index(){
        if (!auth()->user()) return redirect('login');
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'color', 'size'])
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product){
        if (!auth()->user()) return redirect('login');
        \Log::info('Add to cart request:', [
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'color_id' => $request->color_id,
            'size_id' => $request->size_id
        ]);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
            'color_id' => 'nullable|exists:colors,id',
            'size_id' => 'nullable|exists:sizes,id'
        ]);

        // Check if product already exists in cart with same color and size
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->quantity) {
                return redirect()->back()->with('error', 'Not enough stock available.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
            \Log::info('Updated existing cart item:', ['cart_item' => $cartItem->toArray()]);
        } else {
            // Create new cart item
            $newCartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'color_id' => $request->color_id ?: null,
                'size_id' => $request->size_id ?: null,
                'quantity' => $request->quantity
            ]);
            \Log::info('Created new cart item:', ['cart_item' => $newCartItem->toArray()]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->quantity
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $cart->delete();
        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
###################################################################################################
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product', 'color', 'size'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function processCheckout(Request $request)
{
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:credit_card,paypal',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // تحقق من الكمية المتاحة
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->quantity) {
                return redirect()->route('cart.index')->with('error', 'Insufficient stock for product: ' . $item->product->name);
            }
        }

        // Calculate total amount
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Create the order
        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'color_id' => $item->color_id,
                'size_id' => $item->size_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Update product quantity
            $item->product->decrement('quantity', $item->quantity);
        }

        // Clear the cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    }

####################################################################################

public function orders()
{
    $orders = Order::where('user_id', Auth::id())
        ->with(['items.product', 'items.color', 'items.size'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('orders.index', compact('orders'));
}

public function adminOrders()
{
    if (!auth()->user()->hasPermissionTo('manage_orders')) abort(401);

    $orders = Order::with(['user', 'items.product', 'items.color', 'items.size'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('orders.admin', compact('orders'));
}

public function updateStatus(Request $request, Order $order)
{
    if (!auth()->user()->hasPermissionTo('manage_orders')) abort(401);

    $request->validate([
        'status' => 'required|in:pending,processing,completed,cancelled'
    ]);

    $order->update([
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'Order status updated successfully');
}

