<?php
require 'app/models/Customer.php';
require 'app/models/Product.php';
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
    
    public static function handle_register(){
        $params = $_POST;
        $customer = Customer::createFromParams($params);
        
        if($customer->validate($params) && $customer->checkUsername($params)) {
            $customer->save();
            Redirect::to('/', array('message' => 'A new customer registered, sign in next!'));
        }else{
           View::make('customer/register.html', array('errors' => $customer->errors(), 'message' => 'Registering failed, try again', 'params' => $params)); 
        } 
    }
    
    public static function see_profile(){
        self::check_logged_in();
        $customer = self::get_user_logged_in();
        $products = Product::findAllByCustomer($customer->username);
        View::make('customer/profile.html', array('products' => $products, 'customer' => $customer));
    }
    
    public static function alter_profile(){
        $params = $_POST;
        $customer = Customer::createFromParams($params);
        
        if($customer->validate($params)) {
            $customer->update($params);
            Redirect::to('/profile', array('message' => 'Customer info changed!'));
            
        } else {
            View::make('customer/profile.html', array('errors' => $customer->errors(), 'message' => 'Changing customer info failed, try again', 'params' => $params, 'customer' => $customer));
        }
    }
    
    public static function destroy($username){
        self::check_logged_in();
        Customer::delete($username);
        Redirect::to('/', array('message' => 'Profile deleted!'));
    }
}
