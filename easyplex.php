<?php

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    header('location: index.php');
}
require_once('simplexProblem.php');
$request = json_decode($_POST['problem']);

try{
    $problem = new simplexProblem($request->objective);

    $objectiveFunction = new SimplexObjectiveFunction($request->objectiveVar);
    foreach($request->objectiveExp as $variable){
        $objectiveFunction->addVar(new SimplexVariable($variable[0], $variable[1]));    
    }
    $problem->setObjectiveFunction($objectiveFunction);

    foreach($request->restrictions as $requestRestriction){
        $restriction = new SimplexRestriction();
        foreach($requestRestriction->vars as $restrictionvar){
            $restriction->addVar(new SimplexVariable($restrictionvar[0], $restrictionvar[1]));        
        }
        $restriction->setRestrictionValue($requestRestriction->value);
        // fixo por enqunto
        $restriction->setRestrictionType(0);
        $problem->addRestriction($restriction);
    }

    $problem->solve((int)$_POST['maxIterations']);

    $response = array();
    $response['type']    = 1;
    $response['message'] = array();
    $response['message']['bestSolution']   = $problem->getBestSolution();
    $response['message']['otherSolutions'] = $problem->getSolutions();
    $response['message']['analysisBoard']  = $problem->getAnalysisBoard();
    echo json_encode($response);

} catch (Exception $exc){  
    $response = array();
    $response['type'] = 2;
    $response['message'] = $exc->getMessage();

    echo json_encode($response);
}


