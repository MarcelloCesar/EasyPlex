<?php

require_once('simplexProblem.php');

$problem = new SimplexProblem();

$restriction = new SimplexRestriction();
$restriction->addVar(new SimplexVariable(1, 'x1'));
$restriction->setRestrictionValue(4);
$restriction->setRestrictionType(0);
$problem->addRestriction($restriction);

$restriction = new SimplexRestriction();
$restriction->addVar(new SimplexVariable(1, 'x2'));
$restriction->setRestrictionValue(6);
$restriction->setRestrictionType(0);
$problem->addRestriction($restriction);

$restriction = new SimplexRestriction();
$restriction->addVar(new SimplexVariable(3, 'x1'));
$restriction->addVar(new SimplexVariable(2, 'x2'));
$restriction->setRestrictionValue(18);
$restriction->setRestrictionType(0);
$problem->addRestriction($restriction);

$objectiveFunction = new SimplexObjectiveFunction();
$objectiveFunction->addVar(new SimplexVariable(3, 'x1'));
$objectiveFunction->addVar(new SimplexVariable(5, 'x2'));
$problem->setObjectiveFunction($objectiveFunction);

$problem->solve();
//echo $problem->getSolutions();
echo $problem->getBestSolution();








