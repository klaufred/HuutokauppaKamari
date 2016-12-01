<?php

  $routes->get('/', function() {
    product_controller::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/login', function() {
    user_controller::login();
  });
  
  $routes->post('/login', function() {
    user_controller::handle_login();
  });
  
  $routes->post('/logout', function(){
    user_controller::logout();
  });
  
  $routes->get('/register', function() {
    user_controller::register();
  });
  
  $routes->post('/register', function() {
    user_controller::handle_register();
  });
  
  $routes->get('/profile', function() {
    user_controller::see_profile();
  });
  
  $routes->post('/profile', function() {
    user_controller::alter_profile();
  });
  
  $routes->post('/profile/destroy', function($username){
    user_controller::destroy($username);
  });
  
  $routes->post('/product', function() {
      product_controller::store();
  });
  $routes->get('/product/new', function() {
      product_controller::new_product();
  });
  
  $routes->get('/product/:id', function($id) {
      product_controller::product_page($id);
  });
  
  $routes->get('/product/:id/product_modify', function($id) {
      product_controller::product_page_change($id);
  });
  
  $routes->post('/product/:id/product_modify', function($id){
      product_controller::update($id);
  });

  $routes->post('/product/:id/destroy', function($id){
      product_controller::destroy($id);
  });
