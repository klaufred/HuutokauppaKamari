<?php

class Product extends BaseModel{
    public $id, $productName, $description, $minimalPrice, $saleBeginningDate, $saleEndingDate, $customer;
    
    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validations = array(
            'required' => array(
                array('productName'),array('minimalPrice'),array('saleBeginningDate'),array('saleEndingDate')),
            
            'date' => array(
                array('saleBeginningDate'),array('saleEndingDate')),
            
            'dateAfter' => array(
                array('saleEndingDate, saleBeginningDate')),
            
            'lengthMax' => array(
                array('description',500),array('productName',100)),
            
            'numeric' => array(
                array('minimalPrice')),
            
            'min' => array(
                array('minimalPrice',0))
        );
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Product (productName, description, minimalPrice, saleBeginningDate, saleEndingDate, customer) VALUES (:productName, :description, :minimalPrice, :saleBeginningDate, :saleEndingDate, :customer) RETURNING id');
        $query->execute(array('productName' => $this->productName, 'description' => $this->description, 'minimalPrice' => $this->minimalPrice, 'saleBeginningDate' => $this->saleBeginningDate, 'saleEndingDate' => $this->saleEndingDate, 'customer' => $this->customer));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update($id){
        $query = DB::connection()->prepare('UPDATE Product SET productName = :productName, description = :description, saleBeginningDate = :saleBeginningDate, saleEndingDate = :saleEndingDate, minimalPrice = :minimalPrice WHERE id = :id');
        $query->execute(array('productName' => $this->productName, 'description' => $this->description, 'minimalPrice' => $this->minimalPrice, 'saleBeginningDate' => $this->saleBeginningDate, 'saleEndingDate' => $this->saleEndingDate, 'id' => $id));
        $this->id = $id;
    }
    
    public static function delete($id){
        $query = DB::connection()->prepare('DELETE FROM Product WHERE id = :id');
        $query->execute(array('id' => $id));
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Product ORDER BY productName');
        $query->execute();
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $products[] = Product::createFromARow($row);
        }

        return $products;
    }
    
    public static function findWithId($id){
        $query = DB::connection()->prepare('SELECT * FROM Product WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        return Product::createFromARow($row);
    }
    
    public static function findWithName($productName){
        $query = DB::connection()->prepare('SELECT * FROM Product WHERE productName = :productName LIMIT 1');
        $query->execute(array('productName' => $productName));
        $row = $query->fetch();

        return Product::createFromARow($row);
    }
    
    private static function createFromARow($row) {
        if ($row){
            $product = new Product(array(
                'id' => $row['id'],
                'productName' => $row['productname'],
                'description' => $row['description'],
                'minimalPrice' => $row['minimalprice'],
                'saleBeginningDate' => $row['salebeginningdate'],
                'saleEndingDate' => $row['saleendingdate'],
                'customer' => $row['customer']
            ));
            return $product;
        }

        return null;
    }
    
    public static function createFromParams($params) {
        if ($params!=null){
            $product = new Product(array(
                'productName' => $params['productName'],
                'description' => $params['description'],
                'minimalPrice' => $params['minimalPrice'],
                'saleBeginningDate' => $params['saleBeginningDate'],
                'saleEndingDate' => $params['saleEndingDate'],
                'customer' => $params['seller']
            ));
            return $product;
        }

        return null;
    }
}
