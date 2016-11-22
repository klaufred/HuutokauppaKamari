<?php

  require 'app/models/product.php';
  class HelloWorldController extends BaseController{

    public static function sandbox(){
      $game = Product::find(1);
      $machine = Product::all();
      Kint::dump($game);
      Kint::dump($machine);
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
