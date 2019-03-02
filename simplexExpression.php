<?php

require_once('simplexVariable.php');
class SimplexExpression{
    private $_vars;

    public function __construct(){
        $this->_vars = array();
    }

    public function getVarByName($name){        
        return array_key_exists($name, $this->_vars) ? 
               $this->_vars[$name] : null;
    }
	
	public function addVar(SimplexVariable $var){		
        $this->_vars[$var->getName()] = $var; 
	}
	
	public function getVarNames(){		
		return array_keys($this->_vars);
	}
}