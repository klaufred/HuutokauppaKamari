<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;
    public $validations;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }
    public function validate($params){
        $validators=new Valitron\Validator($params);
        $validators->rules($this->validations);
        $this->validators=$validators;
        return $validators->validate();
    }
    
    public function errors(){
        return $this->validators->errors();
    }

    
  }
