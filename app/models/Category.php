<?php

class Category extends BaseModel{
    public $id, $categoryName;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Category ORDER BY categoryName');
        $query->execute();
        $rows = $query->fetchAll();
        $categories = array();
        
        foreach($rows as $row){
            $categories[] = Category::createFromRow($row);
        }
        
        return $categories;
    }
    
    public static function findAllByProduct($id){
        $query = DB::connection()->prepare('SELECT * FROM Category WHERE category.id '
                . 'IN (SELECT productcategory.category FROM Productcategory '
                . 'WHERE productcategory.product = :product)');
        $query->execute(array('product' => $id));
        $rows = $query->fetchAll();
        $categories = array();

        foreach($rows as $row){
            $categories[] = Category::createFromRow($row);
        }

        return $categories;
    }
    
    public static function findWithId($id){
        $query = DB::connection()->prepare('SELECT * FROM Category WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $category = Category::createFromRow($row);

        return $category;
    }

    private static function createFromRow($row) {
        if ($row){
            $category = new Category(array(
                'id' => $row['id'],
                'categoryName' => $row['categoryname']
            ));
            return $category;
        }
        return null;
    }
}
