<?php

class Productcategory extends BaseModel{
    public $id, $category, $product;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Productcategory (category, product) '
                . 'VALUES (:category, :product) RETURNING id');
        $query->execute(array('category' => $this->category, 'product' => $this->product));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public static function deleteWithProduct($product){
        $query = DB::connection()->prepare('DELETE FROM Productcategory WHERE product = :product');
        $query->execute(array('product' => $product));
    }
    
    public static function deleteWithCategory($category){
        $query = DB::connection()->prepare('DELETE FROM Productcategory WHERE category = :category');
        $query->execute(array('category' => $category));
    }
    
    public static function connect($product, $categories){
        foreach ($categories as $category){
            $productcategory = new Productcategory(array(
                'category' => $category,
                'product' => $product));
            $productcategory->save();
        }
    }
}
