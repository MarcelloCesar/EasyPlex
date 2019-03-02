<?php

class SimplexVariable{
    private $_name;
    private $_value;

	public function __construct($value, $name)
	{
		$this->_value = $value;
		$this->_name  = $name;
	}
		
    public function getValue(){
        return $this->_value;
    }

    public function getName(){
        return $this->_name;
    }
}