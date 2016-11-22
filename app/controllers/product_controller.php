<?php
require 'app/models/product.php';
class product_controller extends BaseController{
    public static function index() {
        $products = Product::all();
        View::make('home.html', array('products' => $products));
    }
    
    public static function new_product() {
        View::make('product/new_product.html');
    }
    
    public static function product_page($id) {
        $product = Product::findWithId($id);
        View::make('product/product_page.html', array('product' => $product));
    }

    public static function store(){
        $params = $_POST;
        $product = new Product(array(
          'productName' => $params['productName'],
          'saleBeginningDate' => $params['saleBeginningDate'],
          'saleEndingDate' => $params['saleEndingDate'],
          'description' => $params['description'],
          'minimalPrice' => $params['minimalPrice'],
        ));
        
        $product->save();

        Redirect::to('/product/' . $product->id, array('message' => 'Product added!'));
  }
}
