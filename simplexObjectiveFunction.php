<?php

require_once('simplexExpression.php');

class SimplexObjectiveFunction extends SimplexExpression
{
	private $_objectiveFunctionVariableName;

	public function __construct($objVariable = "Z")
	{
		// What if there is another Z variable in the problem?
		$this->_objectiveFunctionVariableName = $objVariable;
	}  
	
	public function getObjectiveVarName()
	{
		return $this->_objectiveFunctionVariableName;
	}
}