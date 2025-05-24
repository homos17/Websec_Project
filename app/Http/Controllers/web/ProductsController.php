<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;


class ProductsController extends Controller{
    use ValidatesRequests;
    public function ___construct()
    {
        $this->middleware('auth:web')->except(['list', 'category', 'ListByCategory', 'productDetails']);
    }

    public function category(){
        return view('products.category');
    }

    public function ListByCategory($category , Request $request){
        $query = Product::where('category', $category);
        $query->when($request->keywords,
            fn($q)=> $q->where("name","like","%$request->keywords%"));
        $query->when($request->min_price,
            fn($q)=> $q->where("price", ">=", $request->min_price));
        $query->when($request->max_price,
            fn($q)=> $q->where("price", "<=", $request->max_price));
        $query->when($request->order_by,
            fn($q)=> $q->orderBy($request->order_by, $request->order_direction ?? "ASC"));
        $products = $query->get();
        return view('products.list', compact('products', 'category'));
    }

    public function productDetails($id){
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        return view('products.details', compact('product'));
    }

    public function manage() {
        if(!auth()->user()) return redirect('login');

        $products = Product::with(['colors', 'sizes'])->get();
        return view('products.manage', compact('products'));
    }

    public function edit(Request $request, Product $product = null) {
        if(!auth()->user()) return redirect('login');

        $product = $product??new Product();
        $colors = Color::all();
        $sizes = Size::all();
        return view("products.edit", compact('product', 'colors', 'sizes'));
    }

    public function save(Request $request, Product $product = null) {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
            'category' => ['required', 'string', 'max:128'],
            'quantity' => ['required','integer','min:0']
        ]);

        $product = $product??new Product();
        $product->fill($request->all());
        $product->save();

        $product->colors()->sync($request->colors ?? []);
        $product->sizes()->sync($request->sizes ?? []);

        return redirect()->route('products.manage');
    }

    public function delete(Request $request, Product $product) {
        if(!auth()->user()) return redirect('login');
        $product->delete();
        return redirect()->route('products.manage');
    }
}
