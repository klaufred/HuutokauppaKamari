<?php
require 'app/models/Customer.php';
require 'app/models/Product.php';
require 'app/models/Offer.php';
    /**
     * Controller class. Controllers the offers.
     */
class offer_controller extends BaseController{
    
    /**
     *Produce the web page for new offers with the currently logged customer and
     * the product that correlates with the id.
     *
     * @param $id
     */
    public static function new_offer($id){
        self::check_logged_in();
        $product = Product::findWithId($id);
        $customer = self::get_user_logged_in();
        $highestOffer = Offer::getHighestOfferAmount($id);
        View::make('offer/new_offer.html', array('highestOffer' => $highestOffer, 'id' => $id, 'product' => $product, 'customer' => $customer));
    }
    
    /**
     *Save the given offer by getting it's attributes from the information given
     * with the POST as well as the current users username and the correlating 
     * product's id. Also checks the validation before saving the Offer and informs 
     * of mistakes to the user.
     *
     * @param $id
     */
   public static function save_new_offer($id){
       self::check_logged_in();
       $params = $_POST;

       $customer = self::get_user_logged_in();
       $product = Product::findWithId($id);
       $highestOffer = Offer::getHighestOfferAmount($id);
        
       if ($customer->username != $product->customer) {
          $attributes = array(
            'amount' => $params['amount'],
            'offerTime' => date('Y/m/d H:i'),
            'product' => $params['product_id'],
            'customer' => $params['customer_username'],
            'saleBeginningDate' => $product->saleBeginningDate,
            'saleEndingDate' => $product->saleEndingDate
           );
        
          $highestOfferAmount = Offer::getHighestOfferAmount($id);
          $offer = new Offer($attributes);
          
          if (($highestOfferAmount < $offer->amount) && $offer->validate($attributes) && ($offer->amount > $product->minimalPrice)) {
              $offer->save();
              Redirect::to('/product/' . $id, array('message' => 'Your new offer has been saved!'));
          } else {
           View::make('offer/new_offer.html', array('message' => 'Making an offer failed, the offer was either too small or otherwise unacceptable', 'highestOffer' => $highestOffer, 'errors' => $offer->errors(), 'id' => $id, 'product' => $product, 'customer' => $customer));
          }
          
       } else {
          Redirect::to('/product/' . $id, array('message' => 'You can not make an offer on your own product!')); 
       }
    }
    
    
}
