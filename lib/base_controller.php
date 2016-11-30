<?php

  class BaseController{

    public static function get_user_logged_in(){
    // Katsotaan onko user-avain sessiossa
    if(isset($_SESSION['customer'])){
      $customer_username = $_SESSION['customer'];
      // Pyydetään User-mallilta käyttäjä session mukaisella id:llä
      $customer = Customer::findWithUsername($customer_username);

      return $customer;
    }

    // Käyttäjä ei ole kirjautunut sisään
    return null;
  }

    public static function check_logged_in(){
      // Toteuta kirjautumisen tarkistus tähän.
      // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
      if(!isset($_SESSION['customer'])){
        Redirect::to('/login', array('message' => 'Sign in first!'));
      }
    }

  }
