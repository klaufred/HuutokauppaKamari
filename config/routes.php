<?php

  $routes->get('/', function() {
    product_controller::index();
  });
  
  $routes->get('/sellers', function() {
    user_controller::sellers();
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
  
  $routes->get('/profile/:username', function($username) {
    user_controller::see_profile($username);
  });
  
  $routes->post('/profile/:username/destroy', function($username){
    user_controller::destroy($username);
  });
  
  $routes->post('/profile/:username', function($username) {
    user_controller::alter_profile($username);
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
  
  $routes->get('/category/:category_id', function($category_id){
      product_controller::show_by_category($category_id);
  });
  
  $routes->get('/product/:id/offer', function($id) {
      offer_controller::new_offer($id);
  });
  
  $routes->post('/product/:id/offer', function($id) {
      offer_controller::save_new_offer($id);
  });
