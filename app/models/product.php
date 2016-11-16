<?php

class Product extends BaseModel{
    public $id, $productName, $description, $minimalPrice, $saleBeginningDate, $saleEndingDate;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Product (productName, description, minimalPrice, saleBeginningDate, saleEndingDate) VALUES (:productName, :description, :minimalPrice, :saleBeginningDate, :saleEndingDate) RETURNING id');
        $query->execute(array('productName' => $this->productName, 'description' => $this->description, 'minimalPrice' => $this->minimalPrice, 'saleBeginningDate' => $this->saleBeginningDate, 'saleEndingDate' => $this->saleEndingDate));
        $row = $query->fetch();
        $this->id = $row['id'];
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
    
    public static function createFromARow($row) {
        if ($row){
            $product = new Product(array(
                'id' => $row['id'],
                'productName' => $row['productname'],
                'description' => $row['description'],
                'minimalPrice' => $row['minimalprice'],
                'saleBeginningDate' => $row['salebeginningdate'],
                'saleEndingDate' => $row['saleendingdate']
            ));
            return $product;
        }

        return null;
    }
    
    
}
