<?php
require 'app/models/Product.php';
require 'app/models/Customer.php';
require 'app/models/Category.php';
require 'app/models/Productcategory.php';
require 'app/models/Offer.php';
class product_controller extends BaseController{
    
    public static function index() {
        
        $customer = self::get_user_logged_in();
        $categories = Category::all();
        
         $params = $_GET;
         if(isset($params['search'])){
            $options = array('search' => $params['search']);
            $products = Product::all($options);
            View::make('home.html', array('products' => $products, 'customer' => $customer, 'categories' => $categories));

        } else {
           $products = Product::allProductsAndHighestOffer();   
           View::make('home.html', array('products' => $products, 'customer' => $customer, 'categories' => $categories));
        }  
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
        $categories = Category::all();
        
        $attributes = array(
                'productName' => $params['productName'],
                'description' => $params['description'],
                'minimalPrice' => $params['minimalPrice'],
                'saleBeginningDate' => $params['saleBeginningDate'],
                'saleEndingDate' => $params['saleEndingDate'],
                'customer' => self::get_user_logged_in()
            );
        
        $product = new Product($attributes);  
        
        if($product->validate($attributes)) {
            $product->update($id);
            Productcategory::deleteWithProduct($id);
            if (array_key_exists('categories', $params)) {
                Productcategory::connect($id, $params['categories']);
            }
            Redirect::to('/product/' . $product->id, array('message' => 'Product info is edited!'));
        }else{
           $original_product = Product::findWithId($id);
           View::make('product/product_page_change.html', array('errors' => $product->errors(), 'params' => $original_product, 'customer' => $customer, 'categories' => $categories)); 
        }
    }
    
    public static function destroy($id){
        self::check_logged_in();
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        
        if($customer->username == $product->customer){
          Product::delete($id);
          Redirect::to('/', array('message' => 'Product deleted!'));  
        } else {
            Redirect::to('/', array('message' => 'Product could not be deleted!'));
        }
        
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
        $highestOffer = Offer::getHighestOfferAmount($id);
        View::make('product/product_page.html', array('highestOffer' => $highestOffer, 'product' => $product, 'customer' => $customer, 'categories' => $categories));
    }

    public static function store(){
        self::check_logged_in();
        $params = $_POST;
        $customer = self::get_user_logged_in();
        $categories = Category::all();
        
        $attributes = array(
                'productName' => $params['productName'],
                'description' => $params['description'],
                'minimalPrice' => $params['minimalPrice'],
                'saleBeginningDate' => $params['saleBeginningDate'],
                'saleEndingDate' => $params['saleEndingDate'],
                'customer' => self::get_user_logged_in()
            );
        
        $product = new Product($attributes); 
        
        if($product->validate($attributes)) {
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
