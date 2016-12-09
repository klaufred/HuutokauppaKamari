<?php

class offer_controller extends BaseController{
    public static function new_offer($id){
        self::check_logged_in();
        $product = Product::findWithId($id);
        View::make('offer/new_offer.html', array('id' => $id, 'product' => $product));
    }
    
   public static function save_new_offer(){
        
    }
    
    public static function offer_page(){
        
    }
}
