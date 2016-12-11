<?php

class Product extends BaseModel{
    public $id, $productName, $description, $minimalPrice, $saleBeginningDate, $saleEndingDate, $customer, $highestOffer;
    
    public function __construct($attributes){
        parent::__construct($attributes);
        $this->validations = array(
            'required' => array(
                array('productName'),array('minimalPrice'),array('saleBeginningDate'),array('saleEndingDate')),
            
            'date' => array(
                array('saleBeginningDate'),array('saleEndingDate')),
            
            'dateBefore' => array(
                array('saleBeginningDate, saleEndingDate')),
            
            'lengthMax' => array(
                array('description',500),array('productName',100)),
            
            'numeric' => array(
                array('minimalPrice')),
            
            'min' => array(
                array('minimalPrice',0))
        );
    }
    
    public function save($customer){
        $query = DB::connection()->prepare('INSERT INTO Product (productName, description, minimalPrice, saleBeginningDate, saleEndingDate, customer) '
                . 'VALUES (:productName, :description, :minimalPrice, :saleBeginningDate, :saleEndingDate, :customer) RETURNING id');
        $query->execute(array('productName' => $this->productName, 'description' => $this->description, 'minimalPrice' => $this->minimalPrice, 'saleBeginningDate' => $this->saleBeginningDate, 'saleEndingDate' => $this->saleEndingDate, 'customer' => $customer->username));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update($id){
        $query = DB::connection()->prepare('UPDATE Product '
                . 'SET productName = :productName, description = :description, saleBeginningDate = :saleBeginningDate, saleEndingDate = :saleEndingDate, minimalPrice = :minimalPrice WHERE id = :id');
        $query->execute(array('productName' => $this->productName, 'description' => $this->description, 'minimalPrice' => $this->minimalPrice, 'saleBeginningDate' => $this->saleBeginningDate, 'saleEndingDate' => $this->saleEndingDate, 'id' => $id));
        $this->id = $id;
    }
    
    public static function delete($id){
        $query = DB::connection()->prepare('DELETE FROM Product WHERE id = :id');
        $query->execute(array('id' => $id));
    }
    
    public static function all($options){
        $query_string = 'SELECT Product.id, Product.productName, '
                . 'Product.description, Product.minimalPrice, '
                . 'Product.saleBeginningDate, Product.saleEndingDate, Product.customer,'
                . ' MAX(offer.amount) AS highestOffer '
                . 'FROM Product LEFT JOIN Offer ON Product.id = Offer.product';
        
        if(isset($options['search'])){
            $query_string .= ' WHERE productName LIKE :like';
            $options = array('like' => '%' . $options['search'] . '%');
        }
        $query_string .= ' GROUP BY Product.id, Product.productName, Product.description, '
                . 'Product.minimalPrice, Product.saleBeginningDate, Product.saleEndingDate, Product.customer '
                . 'ORDER BY productName;';
        
        $query = DB::connection()->prepare($query_string);
        $query->execute($options);
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $products[] = Product::createFromARowWithOffer($row);
        }

        return $products;
    }
    
    public static function allProductsAndHighestOffer(){
        $query = DB::connection()->prepare('SELECT Product.id, Product.productName, '
                . 'Product.description, Product.minimalPrice, Product.saleBeginningDate, '
                . 'Product.saleEndingDate, Product.customer, MAX(Offer.amount) AS highestOffer '
                . 'FROM Product LEFT JOIN Offer ON Product.id = Offer.product '
                . 'GROUP BY Product.id, Product.productName, Product.description, '
                . 'Product.minimalPrice, Product.saleBeginningDate, Product.saleEndingDate, Product.customer '
                . 'ORDER BY productName;');
        $query->execute();
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $products[] = Product::createFromARowWithOffer($row);
        }

        return $products;
    }
    
    public static function findAllByCustomer($customer){
        $query = DB::connection()->prepare('SELECT Product.id, Product.productName, '
                . 'Product.description, Product.minimalPrice, Product.saleBeginningDate, '
                . 'Product.saleEndingDate, Product.customer, MAX(Offer.amount) AS highestOffer '
                . 'FROM Product LEFT JOIN Offer ON Product.id = Offer.product '
                . 'WHERE Product.customer = :customer '
                . 'GROUP BY Product.id, Product.productName, Product.description, '
                . 'Product.minimalPrice, Product.saleBeginningDate, Product.saleEndingDate, Product.customer;');
        $query->execute(array('customer' => $customer));
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $products[] = Product::createFromARowWithOffer($row);
            
        }

        return $products;
    }
    
    public static function deleteAllByCustomer($customer){
        $query = DB::connection()->prepare('SELECT * FROM Product WHERE customer = :customer');
        $query->execute(array('customer' => $customer));
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $query = DB::connection()->prepare('DELETE FROM Product WHERE id = :id');
            $query->execute(array('id' => $row['id']));
        }
    }
    
    public static function findWithId($id) {
        $query = DB::connection()->prepare('SELECT * FROM Product WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        return Product::createFromARow($row);
    }

    public static function findWithCategory($id){
        $query = DB::connection()->prepare('SELECT Product.id, Product.productName, '
                . 'Product.description, Product.minimalPrice, Product.saleBeginningDate, '
                . 'Product.saleEndingDate, Product.customer, MAX(Offer.amount) AS highestOffer '
                . 'FROM Product LEFT JOIN Offer ON Product.id = Offer.product '
                . 'WHERE product.id IN '
                . '(SELECT productcategory.product FROM Productcategory WHERE productcategory.category = :id )'
                . 'GROUP BY Product.id, Product.productName, Product.description, '
                . 'Product.minimalPrice, Product.saleBeginningDate, Product.saleEndingDate, Product.customer;');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $products = array();

        foreach($rows as $row){
            $products[] = Product::createFromARowWithOffer($row);
        }

        return $products;
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
                'saleEndingDate' => $row['saleendingdate'],
                'customer' => $row['customer']
            ));
            return $product;
        }

        return null;
    }
    
    public static function createFromARowWithOffer($row) {
        if ($row){
            $product = new Product(array(
                'id' => $row['id'],
                'productName' => $row['productname'],
                'description' => $row['description'],
                'minimalPrice' => $row['minimalprice'],
                'saleBeginningDate' => $row['salebeginningdate'],
                'saleEndingDate' => $row['saleendingdate'],
                'customer' => $row['customer'],
                'highestOffer' => $row['highestoffer']
            ));
            return $product;
        }

        return null;
    }
}
