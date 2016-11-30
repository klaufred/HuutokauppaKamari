<?php

  require 'app/models/product.php';
  class HelloWorldController extends BaseController{

    public static function sandbox(){
     $doom = new Product(array(
                'productName' => 'd',
                'description' => '',
                'minimalPrice' => 'hhh',
                'saleBeginningDate' => 'h',
                'saleEndingDate' => 'th'
      ));
   $errors = $doom->errors();

  Kint::dump($errors);
    }
    
    public static function front_page(){
    View::make('home.html');
    }
    
    public static function login(){
    View::make('login.html');
    }
  
    public static function register(){
    View::make('register.html');
    }
    
    public static function product_page(){
    View::make('product_page.html');
    }
    
    public static function product_page_change(){
    View::make('product_page_change.html');
    }
  }
