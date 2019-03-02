<?php

require_once ('simplexExpression.php');

class SimplexRestriction extends SimplexExpression{

	//public static const int RESTRICTION_MINOR_EQUAL   = 0;
	//public static const int RESTRICTION_GREATER_EQUAL = 1;
	
    private $_restrictionValue;
    private $_fillerVar;
	private $_restrictionType;
	

    public function __construct(){
        parent::__construct();
		
		$this->_fillerVar = null;
    }

    public function getFillerVar(){
		if(!empty($this->_fillerVar)){
			return $this->_fillerVar;
		}
		
		// throw excp pq precisar gerar a partir do problema, 
		// vai ter que ser um metodo do problema pra gerar a funcao da restricao
        
    }    
	
	public function setFillerVar(SimplexVariable $filler){
		$this->_fillerVar = $filler;
	}
	
	public function setRestrictionValue( $value){
		$this->_restrictionValue = $value;
	}
	
	public function getRestrictionValue()
	{
		return $this->_restrictionValue;
	}
	public function getVarByName($varName){
		$var = parent::getVarByName($varName);
		
		if(empty($var) && ($varName == $this->_fillerVar->getName())){
			$var = $this->_fillerVar;			
		}
		return $var;
	}
	
	public function setRestrictionType( $type){
		$this->_restrictionType = $type;
	}
}
