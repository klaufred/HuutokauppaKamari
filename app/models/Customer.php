<?php

class Customer extends BaseModel{
    public $username, $password, $address, $email, $confirmation;
    
    public function __construct($attributes){
        parent::__construct($attributes);
        
        $this->validations = array(
            'required' => array(
                array('username'),array('password'),array('address'),array('email'),array('confirmation')),
            
            'lengthMax' => array(
                array('username',50),array('password',50),array('address',250),array('email',150)),
            
            'equals' => array(
                array('password','confirmation')),
            
            'email' => array(
                array('email'))
        );
    }
    
    public static function authenticate($username, $password){
        $query = DB::connection()->prepare('SELECT * FROM Customer WHERE username = :username AND password = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();
        if($row){
          return Customer::createFromRow($row);
        }else{
          return null;
        }
    }

    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Customer (username, password, address, email) VALUES (:username, :password, :address, :email)');
        $query->execute(array('username' => $this->username, 'password' => $this->password, 'address' => $this->address, 'email' => $this->email));
    }
    
    public function update($id){
        $query = DB::connection()->prepare('UPDATE Customer SET username = :username, password = :password, address = :address, email = :email WHERE username = :id');
        $query->execute(array('username' => $this->username, 'password' => $this->password, 'address' => $this->address, 'email' => $this->email, 'id' => $id));
        $this->username = $username;
    }
    
    public static function delete($username){
        $query = DB::connection()->prepare('DELETE FROM Customer WHERE username = :username');
        $query->execute(array('username' => $username));
    }
    
    public static function all(){
        $query = DB::connection()->prepare('SELECT * FROM Customer ORDER BY username');
        $query->execute();
        $rows = $query->fetchAll();
        $customers = array();

        foreach($rows as $row){
            $customers[] = Customer::createFromRow($row);
        }

        return $customers;
    }
    
    public static function findWithUsername($username){
        $query = DB::connection()->prepare('SELECT * FROM Customer WHERE username = :username');
        $query->execute(array('username' => $username));
        $row = $query->fetch();

        return Customer::createFromRow($row);
    }
    
    private static function createFromRow($row) {
        if ($row){
            $customer = new Customer(array(
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email'],
                'address' => $row['address']
            ));
            return $customer;
        }
        return null;
    }
    
    

}
