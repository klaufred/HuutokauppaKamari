<?php

class product_controller extends BaseController{
    public static function index() {
        $products = Product::all();
        View::make('home.html', array('products' => $products));
    }
    
    public static function product_page($id) {
        $product = Product::findWithId($id);
        View::make('product_page.html', array('product' => $product));
    }


}
