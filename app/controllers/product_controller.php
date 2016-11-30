<?php
require 'app/models/product.php';
class product_controller extends BaseController{
    
    public static function index() {
        $products = Product::all();
        $customer = self::get_user_logged_in();
        View::make('home.html', array('products' => $products, 'customer' => $customer));
    }
    
    public static function product_page_change($id){
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        self::check_logged_in();
        View::make('product/product_page_change.html', array('params' => $product, 'customer' => $customer));
    }
    
    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $customer = self::get_user_logged_in();
        $product = Product::createFromParams($params);

        if($product->validate($params)) {
            $product->update($id);
            Redirect::to('/product/' . $product->id, array('message' => 'Product info is edited!'));
        }else{
           View::make('product/product_page_change.html', array('errors' => $product->errors(), 'params' => $product, 'customer' => $customer)); 
        }
    }
    
    public static function destroy($id){
        self::check_logged_in();
        Product::delete($id);
        Redirect::to('/', array('message' => 'Product deleted!'));
    }

    public static function new_product() {
        $customer = self::get_user_logged_in();
        self::check_logged_in();
        View::make('product/new_product.html', array('customer' => $customer));
    }
    
    public static function product_page($id) {
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        View::make('product/product_page.html', array('product' => $product, 'customer' => $customer));
    }

    public static function store(){
        self::check_logged_in();
        $params = $_POST;
        $customer = self::get_user_logged_in();
        $product = Product::createFromParams($params);
        
        
        if($product->validate($params)) {
            $product->save($customer);
            Redirect::to('/product/' . $product->id, array('message' => 'Product added!'));
        }else{
           View::make('product/new_product.html', array('errors' => $product->errors(), 'message' => 'Adding product failed, try again', 'params' => $params, 'customer' => $customer)); 
        }
        

        
  }
}
