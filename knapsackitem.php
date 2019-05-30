<?php

class KnapsackItem {
    private $name;
    private $weight;
    private $value;

    public function __construct($name, $weight, $value)
    {
        $this->_name   = $name;
        $this->_weight = $weight;
        $this->_value  = $value;
    }
    
    public function getName()  
    {
        return $this->_name;
    }  

    public function getWeight()
    {
        return $this->_weight;
    }

    public function getValue()
    {
        return $this->_value;
    }
}