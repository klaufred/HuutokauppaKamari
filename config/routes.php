<?php

  $routes->get('/', function() {
    product_controller::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });
  
  $routes->get('/register', function() {
    HelloWorldController::register();
  });
  
  $routes->get('/product/:id', function($id) {
      product_controller::product_page($id);
  });
  
  $routes->get('/product_modify', function() {
    HelloWorldController::product_page_change();
  });
