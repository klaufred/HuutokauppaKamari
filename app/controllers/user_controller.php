<?php
require 'app/models/Customer.php';
require 'app/models/Product.php';
require 'app/models/Offer.php';
class user_controller extends BaseController{
    
    public static function sellers() {
        $customer = self::get_user_logged_in();
        $sellers = Customer::findSellers();
        View::make('customer/sellers.html', array('sellers' => $sellers, 'customer' => $customer));
    }
    
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
            Redirect::to('/', array('message' => 'Welcome ' . $customer->username . '!'));
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
        
        $attributes = array(
            'username' => $params['username'],
            'password' => $params['password'],
            'confirmation' => $params['confirmation'],
            'address' => $params['address'],
            'email' => $params['email']
        );
        
        $customer = new Customer($attributes);
        
        if($customer->validate($attributes) && $customer->checkUsername($params['username'])) {
            $customer->save();
            $_SESSION['customer'] = $customer->username;
            Redirect::to('/', array('message' => 'A new customer registered!', 'customer' => $customer));
        }else{
           View::make('customer/register.html', array('errors' => $customer->errors(), 'message' => 'Registering failed, try again', 'params' => $params)); 
        } 
    }
    
    public static function see_profile($username){
        $customer = self::get_user_logged_in();
        $products = Product::findAllByCustomer($username);
        $profileOwner = Customer::findWithUsername($username);
        $offers = Offer::findAllByCustomer($username);
        
        View::make('customer/profile.html', array('offers' => $offers, 'products' => $products, 'customer' => $customer, 'profileOwner' => $profileOwner));
    }
    
    public static function alter_profile($username){
        self::check_logged_in();
        $params = $_POST;
        
        $attributes = array(
            'username' => $username,
            'password' => $params['password'],
            'confirmation' => $params['confirmation'],
            'address' => $params['address'],
            'email' => $params['email']
        );
        
        $customer = new Customer($attributes);
        
        if($customer->validate($attributes) && $customer->username == $username) {
            $customer->update();
            Redirect::to('/profile/' . $username, array('message' => 'Customer info changed!'));
            
        } else {
            $products = Product::findAllByCustomer($username);
            $user = self::get_user_logged_in();
            $offers = Offer::findAllByCustomer($username);
            View::make('customer/profile.html', array('offers' => $offers, 'products' => $products,'errors' => $customer->errors(), 'profileOwner' => $user,  'message' => 'Changing customer info failed, try again', 'params' => $params, 'customer' => $user));
        }
    }
    
    public static function destroy($username){
        self::check_logged_in();
        $user = self::get_user_logged_in();
        if($user->username == $username){
           Product::deleteAllByCustomer($username);
           Offer::deleteAllByCustomer($username);
           Customer::delete($username);
           $_SESSION['customer'] = null;
           Redirect::to('/', array('message' => 'Profile deleted!')); 
        } else {
           Redirect::to('/', array('message' => 'Profile could not be deleted deleted!'));
        }
        
    }
}
