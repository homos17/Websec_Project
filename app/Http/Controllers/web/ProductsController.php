<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

class ProductsController extends Controller{

    public function category(){
        return view('products.category');
    }

    public function show_Women(){
        return view('products.women');
    }
    public function show_men(){
        return view('products.men');
    }
    public function show_kids(){
        return view('products.kids');
    }



}
