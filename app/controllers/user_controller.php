<?php
require 'app/models/customer.php';
class user_controller extends BaseController{
    
    public static function login(){
        View::make('customer/login.html');
    }
  
    public static function handle_login(){
        $params = $_POST;
        
        $customer = Customer::authenticate($params['username'], $params['password']);

        if(!$customer){
            View::make('customer/login.html', array('message' => 'Wrong username or password!', 'username' => $params['username']));
        }else{
            $_SESSION['customer'] = $customer->username;
            Redirect::to('/', array('message' => 'Welcome back ' . $customer->username . '!'));
        }
    }

    public static function logout(){
        $_SESSION['customer'] = null;
        Redirect::to('/', array('message' => 'You have been signed out!'));
    }
    
    public static function register(){
        View::make('customer/register.html');
    }
}
