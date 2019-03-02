<?php
require_once('simplexRestriction.php');
require_once('simplexObjectiveFunction.php');

class SimplexProblem {

    private $_objective;
    private $_objectiveVariable;
    private $_objectiveFunction;
	private $_varNames;
	private $_fillerVarNames;
    private $_restrictions;
	private $_solutions;
    private $_problemTable;
	private $_contNegativeObjetiveVariables;
	private $_bestSolution;
	

	public function __construct()
	{
		$this->_restrictions   = array();
		$this->_varNames       = array();
		$this->_solutions      = array();
		$this->_fillerVarNames = array();
	}
    
    public function setObjectiveFunction($objFunction)
    {
        $this->_objectiveFunction = $objFunction;
    }

	public function addRestriction(SimplexRestriction $restriction){
		//throw exception instanceof
		
		if(!in_array($restriction, $this->_restrictions)){
			$this->_restrictions[] = $restriction;			
			foreach($restriction->getVarNames() as $varName){
				if(!in_array($varName, $this->_varNames)){
					$this->_varNames[] = $varName;
				}
			}
		}
	}
	
	public function getSolutions(){		
		return json_encode( $this->_solutions);
	}


	public function getBestSolution()
	{
		return json_encode($this->_bestSolution);
	}
	
    public function solve()
    {
        $this->_validProblem();
		$this->_createFillerVariables();		
		$this->_createProblemTable();   		
			
		while($this->_contNegativeObjetiveVariables > 0)
		{
			$pivotColumn = $this->_findPivotColumn();

			$pivotLine   = $this->_findPivotLine($pivotColumn);

			$this->_changeVariables($pivotColumn, $pivotLine);
			
			$this->_balancePivotLine($pivotColumn, $pivotLine);			
			
			$this->_balancePivotColumn($pivotColumn, $pivotLine);				
			
			$this->_addSolution();			

			$this->_contNegativeObjetiveVariables--;
		}
		$this->_findBestAnswer();		
    }

    private function _validProblem(){

    }

    private function _createProblemTable()
    {
        // Form base column
        $problemTable = array();
        $problemTable['base'] = array();
        foreach($this->_restrictions as $restriction){
            $problemTable['base'][] = $restriction->getfillerVar()->getName();            
        }
        $problemTable['base'][] = $this->_objectiveFunction->getObjectiveVarName();

		//Form vars columns		
        foreach(array_merge($this->_varNames, $this->_fillerVarNames) as $var){
            foreach($this->_restrictions as $restriction){        

                //Melhorar isto
				$variable = $restriction->getVarByName($var);
                $problemTable[$var][] = empty($variable) ? 0 : $variable->getValue();                
            }
            $variable = $this->_objectiveFunction->getVarByName($var);
			
			if(empty($variable)){
				$problemTable[$var][] = 0;				
			} else {
				$number = $variable->getValue() * (-1);
				$problemTable[$var][] = $number;
				
				if($number < 0)
				{
					$this->_contNegativeObjetiveVariables++;
				}
			}
        }        

        //form b column
        $problemTable['b'] = array();
        foreach($this->_restrictions as $restriction){
            $problemTable['b'][] = $restriction->getRestrictionValue();
        }
        $problemTable['b'][] = 0;
		
		$this->_problemTable = $problemTable;
		$this->_addSolution();		
    }    
	
	private function _createFillerVariables(){
		$fillerNumber = 1;
		foreach($this->_restrictions as $restriction)
		{
			$varName = "f$fillerNumber";
			$fillerVar = new SimplexVariable(1, $varName);
			$restriction->setFillerVar($fillerVar);
			$this->_fillerVarNames[] = $fillerVar->getName();
			$fillerNumber++;
		}
	}
	
	private function _findPivotColumn(){
		$minorValue = 0;		
		$keyColumn  = null;
		foreach($this->_problemTable as $key => $column){
			// The last value of the column is the value for the objective function			
			$number = end($column);
			if($number < $minorValue)
			{
				$minorValue = $number;
				$keyColumn  = $key;
			}	
		}
		return $keyColumn;
	}
	
	private function _findPivotLine($pivotColumn)
	{
		$pivotLine = null;
		$minorPositiveValue = 0xFFFFFFFFFFFFFFFFFF;
		
		foreach($this->_problemTable['b'] as $key => $bNumber)
		{	
			//Inserir verificacao divisao por 0 e tirar o arroba pelo amor de cristo
			//Como tratar se der dois numeros iguais?
			//echo json_encode($this->_problemTable[$pivotColumn]);
			@$result = $bNumber / $this->_problemTable[$pivotColumn][$key];
			if($result < $minorPositiveValue && $result > 0){
				$pivotLine = $key;
				$minorPositiveValue = $result;
			}
			//Adicionar que nao tem solucao quando n tem maior q zero
		}
		return $pivotLine;
	}

	private function _changeVariables($pivotColumn, $pivotLine)
	{
		$this->_problemTable['base'][$pivotLine] = $pivotColumn;
	}

	private function _balancePivotLine($pivotColumn, $pivotLine)
	{
		$pivot = $this->_problemTable[$pivotColumn][$pivotLine];
		if($pivot != 1)
		{			
			foreach($this->_problemTable as $key => &$column)
			{
				//Ignore base column
				if($key == 'base')
				{
					continue;
				}
				// Acredito que nao tem como o pivot ser 0, pois nao existe 
				// divisao por zero na procura de pivot
				$column[$pivotLine] /= $pivot;
			}
		}
	}

	private function _balancePivotColumn($pivotColumn, $pivotLine)
	{		
		foreach($this->_problemTable[$pivotColumn] as $line => $lineValue)
		{
			if($line == $pivotLine)
			{
				continue;
			}

			if($lineValue != 0)
			{
				//$originalLineValue = $lineValue; apagar se nao necessario
				foreach($this->_problemTable as $key => &$column)
				{
					if($key == 'base')
					{
						continue;
					}

					$column[$line] = $this->_problemTable[$key][$pivotLine] *
									 ($lineValue * (-1)) 					+
									 $column[$line];
				}				
			}			
		}
	}

	private function _addSolution()
	{
		$this->_solutions[]  = str_replace("\"", "", json_encode($this->_problemTable));
	}

	// funcao de debug, remover
	private function echoTable()
	{
		echo json_encode($this->_problemTable) . "<BR><BR>";
	}

	private function _findBestAnswer()
	{
		$bestSolution = array();
		foreach($this->_problemTable['base'] as $key => $variableName)
		{
			if(!in_array($variableName, $this->_fillerVarNames))
			{
				$bestSolution[$variableName] = $this->_problemTable['b'][$key];
			}
		}

		$this->_bestSolution = $bestSolution;
	}
}