<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller{

    public function category(){
        return view('products.category');
    }

    public function ListByCategory($category){
    $products = Product::where('category', $category)->get();
    return view('products.list', compact('products', 'category'));
}




}
