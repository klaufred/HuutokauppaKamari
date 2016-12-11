<?php
require 'app/models/Customer.php';
require 'app/models/Product.php';
require 'app/models/Offer.php';
class offer_controller extends BaseController{
    
    public static function new_offer($id){
        self::check_logged_in();
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        View::make('offer/new_offer.html', array('id' => $id, 'product' => $product, 'customer' => $customer));
    }
    
   public static function save_new_offer($id){
       self::check_logged_in();
       $params = $_POST;

       $customer = self::get_user_logged_in();
       $product = Product::findWithId($id);
        
       if ($customer->username != $product->customer) {
          $attributes = array(
            'amount' => $params['amount'],
            'offerTime' => time(),
            'product' => $params['product_id'],
            'customer' => $params['customer_username']
           );
        
          $highestOfferAmount = Offer::getHighestOfferAmount($id);
          $offer = new Offer($attributes);
          
          if (($highestOfferAmount < $offer->amount) && $offer->validate($attributes) && ($offer->amount > $product->minimalPrice)) {
              $offer->save();
              Redirect::to('/product/' . $id, array('message' => 'Your new offer has been saved!'));
          } else {
           View::make('offer/new_offer.html', array('message' => 'Making an offer failed, the offer was either too small or otherwise unacceptable','id' => $id, 'product' => $product, 'customer' => $customer));
          }
          
       } else {
          Redirect::to('/product/' . $id, array('message' => 'You can not make an offer on your own product!')); 
       }
    }
    
    
}
