<?php
require_once('simplexRestriction.php');
require_once('simplexObjectiveFunction.php');

define('MAX_ITERATIONS_DEFAULT', 0xFFFFFF);

class SimplexProblem {

	private $_objective;
    private $_objectiveFunction;
	private $_varNames;
	private $_fillerVarNames;
    private $_restrictions;
	private $_solutions;
    private $_problemTable;	
	private $_bestSolution;
	private $_analysisBoard;
	

	public function __construct($objective)
	{
		$this->_restrictions   = array();
		$this->_varNames       = array();
		$this->_solutions      = array();
		$this->_fillerVarNames = array();		
		$this->_bestSolution   = array();
		$this->_analysisBoard  = array();

		if($objective == 'max' || $objective == 'min'){
			$this->_objective = $objective;
		} else {
			throw new Exception ("Objetivo do problema inválido.");
		}
	}
    
    public function setObjectiveFunction($objFunction)
    {
        $this->_objectiveFunction = $objFunction;
    }

	public function addRestriction(SimplexRestriction $restriction){
		//throw exception instanceof
		
		//if(!in_array($restriction, $this->_restrictions)){
			$this->_restrictions[] = $restriction;			
			foreach($restriction->getVarNames() as $varName){
				if(!in_array($varName, $this->_varNames)){
					$this->_varNames[] = $varName;
				}
			}
		//}
	}
	
	public function getSolutions(){		
		return $this->_solutions;
	}


	public function getBestSolution()
	{
		return $this->_bestSolution;
	}

	public function getAnalysisBoard()
	{
		return $this->_analysisBoard;
	}
	
    public function solve($maxIterations = MAX_ITERATIONS_DEFAULT)
    {
        $this->_validProblem();
		$this->_createFillerVariables();		
		$this->_createProblemTable();   				
		
		$iterationsCount = 1;
		if($maxIterations === 0){
			throw new Exception('Número máximo de iterações atingido. Tente aumentar o número máximo de iterações. Se isto não resolver, o problema provavelmente possui infinitas soluções.');
		}

		while(($this->_contNegativeObjetiveVariables() > 0) && ($iterationsCount <= $maxIterations))
		{
			$pivotColumn = $this->_findPivotColumn();

			$pivotLine   = $this->_findPivotLine($pivotColumn);

			$this->_changeVariables($pivotColumn, $pivotLine);
			
			$this->_balancePivotLine($pivotColumn, $pivotLine);			
			
			$this->_balancePivotColumn($pivotColumn, $pivotLine);				
			
			$this->_addSolution();	
			$iterationsCount++;					
		}
		
		$this->_findBestAnswer();	
		$this->_findAnalysis();	
    }

    private function _validProblem(){

	}
	
	private function _contNegativeObjetiveVariables()
	{
		$numberOfNegativeVariables = 0;
		foreach(array_merge($this->_varNames, $this->_fillerVarNames) as $varColumn)
		{
			if(end($this->_problemTable[$varColumn]) < 0){
				$numberOfNegativeVariables++;
			}
		}

		return $numberOfNegativeVariables;
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

				//Verifica se o problema é de maximizacao
				if($this->_objective == 'max'){
					// Em um problema de maximização, a linha do objetivo é criada invertida
					$number = $variable->getValue() * (-1);
				} else {
					// Em um problema de minimização, não é feita inversão da linha 
					$number = $variable->getValue();
				}

				$problemTable[$var][] = $number;
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
		$theresNoSolution = true;
		$minorPositiveValue = 0xFFFFFFFFFFFFFFFFFF;
		
		foreach($this->_problemTable['b'] as $key => $bNumber)
		{				
			if($this->_problemTable[$pivotColumn][$key] !== 0){
				$result = $bNumber / $this->_problemTable[$pivotColumn][$key];
				if($result < $minorPositiveValue && $result > 0){
					$pivotLine = $key;
					$minorPositiveValue = $result;
					$theresNoSolution = false;
				}
			}
		}

		if($theresNoSolution){
			throw new Exception("Este problema não possui solução.");
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
		$this->_solutions[]  = $this->_problemTable;
	}

	private function _findBestAnswer()
	{
		$bestSolution = array();
		$interestVariables   = array_merge(
			$this->_varNames,
			$this->_fillerVarNames
		);
		$interestVariables[] = $this->_objectiveFunction->getObjectiveVarName();		
		
		foreach($interestVariables as $variableName){		
			
			/**** alterar este bloco para usar a funcao  _findValueForVar */
			$key = array_search($variableName, $this->_problemTable['base']);

			if($key !== false){
				$bestSolution[$variableName] = $this->_problemTable['b'][$key];

				if(
					$variableName == $this->_objectiveFunction->getObjectiveVarName() &&
					$this->_objective == 'min'
				){
					$bestSolution[$variableName] *= -1;
				}
			} else {				
				$bestSolution[$variableName] = 0;
			}			
			/****************************/
		}

		$this->_bestSolution = $bestSolution;
	}

	private function _findAnalysis()
	{
		$analysisBoard = array();
		$interestVariables   = array_merge(
			$this->_varNames,
			$this->_fillerVarNames
		);
		$interestVariables[] = $this->_objectiveFunction->getObjectiveVarName();

		$analysisBoard['Variável'] = $interestVariables;

		//Recupera os valores iniciais e finais
		$analysisBoard['Valor Inicial']     = array();
		$analysisBoard['Valor Final']       = array();
		$analysisBoard['Preço Sombra']      = array();
		foreach($analysisBoard['Variável'] as $var){
			$analysisBoard['Valor Inicial'][] = $this->_findValueForVar(0, $var);
			
			if($this->_objective == 'min' && $var == $this->_objectiveFunction->getObjectiveVarName()){
				$analysisBoard['Valor Final'][]   = $this->_findValueForVar(
					count($this->_solutions) - 1, $var
				) * -1;
			} else {
				$analysisBoard['Valor Final'][]   = $this->_findValueForVar(
					count($this->_solutions) - 1, $var
				);
			}
			
			$maxMin = $this->_findMaxMinIncreasing($var);
			$analysisBoard['Max. Aumento'][] = $maxMin[0];
			$analysisBoard['Min. Redução'][] = $maxMin[1];
			$analysisBoard['Preço Sombra'][] = $this->_findShadowPrice($var);
		}

		$this->_analysisBoard = $analysisBoard;
	}

	private function _findValueForVar($solutionNumber, $variable)
	{
		$key = array_search($variable, $this->_solutions[$solutionNumber]['base']);
		if($key !== false){			
			return $this->_solutions[$solutionNumber]['b'][$key];
		} else {
			return 0;
		}		
	}

	private function _findShadowPrice($variable)
	{
		if(in_array($variable, $this->_fillerVarNames)){
			$key = array_search(
				$this->_objectiveFunction->getObjectiveVarName(),
				$this->_problemTable['base']
			);

			return $this->_problemTable[$variable][$key];
		} else {
			return '-';
		}
	}

	private function _findMaxMinIncreasing($variable)
	{
		$minorPositiveValue =  0xFFFFFFFFFFFFFFFFFF;
		$majorNegativeValue = -0xFFFFFFFFFFFFFFFFFF;
		$minorKey = null;
		$majorKey = null;

		if(in_array($variable, $this->_fillerVarNames)){
			$compareNumbers = array();
			foreach($this->_problemTable['b'] as $key => $element){
				if($key == count($this->_problemTable['b']) - 1 ){
					continue;
				}

				if($this->_problemTable[$variable][$key] != 0){
					$compareNumbers[] = ($element / $this->_problemTable[$variable][$key]) * -1;
				}				
			}

			foreach($compareNumbers as $key => $number){
				if($number < 0){
					if($number > $majorNegativeValue){
						$majorNegativeValue = $number;
						$majorKey = $key;
					}
				} else {
					if($number < $minorPositiveValue){
						$minorPositiveValue = $number;
						$minorKey = $key;
					}
				}
			}
			
			$answer = array();
			if($minorKey !== null){
				$answer[] = abs($compareNumbers[$minorKey]);
			} else {
				$answer[] = 'INF';
			}

			if($majorKey !== null){
				$answer[] = abs($compareNumbers[$majorKey]);
			} else {
				$answer[] = '-INF';
			}
			
			return $answer;
		} else {
			return array('-', '-');
		}	
	}
}