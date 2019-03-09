<?php

require_once('simplexProblem.php');
$request = json_decode($_POST['problem']);

$problem = new simplexProblem();

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

$problem->solve();
echo $problem->getBestSolution();


