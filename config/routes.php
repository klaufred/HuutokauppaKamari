<?php

  $routes->get('/', function() {
    HelloWorldController::front_page();
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
  
  $routes->get('/product', function() {
  HelloWorldController::product_page();
  });
  
  $routes->get('/product_modify', function() {
  HelloWorldController::product_page_change();
  });
