<?php
require 'app/models/Product.php';
require 'app/models/Customer.php';
require 'app/models/Category.php';
require 'app/models/Productcategory.php';
class product_controller extends BaseController{
    
    public static function index() {
        $products = Product::all();
        $customer = self::get_user_logged_in();
        $categories = Category::all();
        View::make('home.html', array('products' => $products, 'customer' => $customer, 'categories' => $categories));
    }
    
    public static function product_page_change($id){
        self::check_logged_in();
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        $categories = Category::all();
        
        View::make('product/product_page_change.html', array('params' => $product, 'customer' => $customer, 'categories' => $categories));
    }
    
    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $customer = self::get_user_logged_in();
        $product = Product::createFromParams($params, $customer);
        $categories = Category::findAllByProduct($id);

        if($product->validate($params)) {
            $product->update($id);
            Productcategory::deleteWithProduct($id);
            if (array_key_exists('categories', $params)) {
                Productcategory::connect($id, $params['categories']);
            }
            Redirect::to('/product/' . $product->id, array('message' => 'Product info is edited!'));
        }else{
           View::make('product/product_page_change.html', array('errors' => $product->errors(), 'params' => $product, 'customer' => $customer, 'categories' => $categories)); 
        }
    }
    
    public static function destroy($id){
        self::check_logged_in();
        Product::delete($id);
        Redirect::to('/', array('message' => 'Product deleted!'));
    }

    public static function new_product() {
        self::check_logged_in();
        $customer = self::get_user_logged_in();
        $categories = Category::all();
        View::make('product/new_product.html', array('customer' => $customer, 'categories' => $categories));
    }
    
    public static function product_page($id) {
        $product = Product::findWithId($id);
        $categories = Category::findAllByProduct($id);
        $customer = self::get_user_logged_in();
        View::make('product/product_page.html', array('product' => $product, 'customer' => $customer, 'categories' => $categories));
    }

    public static function store(){
        $params = $_POST;
        $customer = self::get_user_logged_in();
        $product = Product::createFromParams($params, $customer);
        $categories = Category::all();
        
        if($product->validate($params)) {
            $product->save($customer);
            if (array_key_exists('categories', $params)) {
                Productcategory::connect($product->id, $params['categories']);
            }
            Redirect::to('/product/' . $product->id, array('message' => 'Product added!'));
        }else{
           View::make('product/new_product.html', array('errors' => $product->errors(), 'message' => 'Adding product failed, try again', 'categories' => $categories, 'params' => $params, 'customer' => $customer)); 
        } 
    }
    
    public static function show_by_category($id) {
        $products = Product::findWithCategory($id);
        $category = Category::findWithId($id);
        
        View::make('category.html', array('products' => $products, 'category' => $category));
    }
}
