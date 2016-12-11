<?php

class Offer extends BaseModel{
    public $id, $amount, $offerTime, $product, $customer, $productName;
    
    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validations = array(
            'required' => array(
                array('amount'),array('product'),array('customer')),

            'numeric' => array(
                array('amount')),
            
            'min' => array(
                array('amount',0))
        );
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Offer (amount, offerTime, product, customer) '
                . 'VALUES (:amount, NOW(), :product, :customer) RETURNING id');
        $query->execute(array('amount' => $this->amount, 'product' => $this->product, 'customer' => $this->customer));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Offer');
        $query->execute();
        $rows = $query->fetchAll();
        $offers = array();

        foreach($rows as $row){
            $offers[] = Offer::createFromARow($row);
        }

        return $offers;
    }
    
    public static function findAllByCustomer($customer){
        $query = DB::connection()->prepare('SELECT Offer.id, Offer.product, '
                . 'Offer.customer, Offer.amount, Offer.offerTime, Product.productName '
                . 'FROM Offer LEFT JOIN Product ON Product.id = Offer.product '
                . 'WHERE Offer.customer = :customer ORDER BY Product.productName');
        $query->execute(array('customer' => $customer));
        $rows = $query->fetchAll();
        $offers = array();

        foreach($rows as $row){
            $offers[] = new Offer(array(
                'id' => $row['id'],
                'amount' => $row['amount'],
                'offerTime' => date("Y-m-d H:i:s",  strtotime($row['offertime'])),
                'product' => $row['product'],
                'customer' => $row['customer'],
                'productName' => $row['productname']
            ));
        }

        return $offers;
    }
    
    public static function getHighestOfferAmount($id){
        $query = DB::connection()->prepare('SELECT max(amount) FROM Offer WHERE product = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if($row){
            return $row['max'];
        }
        
        return null;
        
    }
    
    public static function deleteAllByCustomer($customer){
        $query = DB::connection()->prepare('SELECT * FROM Offer WHERE customer = :customer');
        $query->execute(array('customer' => $customer));
        $rows = $query->fetchAll();

        foreach($rows as $row){
            $query = DB::connection()->prepare('DELETE FROM Offer WHERE id = :id');
            $query->execute(array('id' => $row['id']));
        }
    }

    
    public static function createFromARow($row) {
        if ($row){
            $offer = new Offer(array(
                'id' => $row['id'],
                'amount' => $row['amount'],
                'offerTime' => date("Y-m-d H:i:s",  strtotime($row['offertime'])),
                'product' => $row['product'],
                'customer' => $row['customer']
            ));
            return $offer;
        }
        return null;
    }
}
