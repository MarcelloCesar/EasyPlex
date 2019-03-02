<?php

require_once('simplexProblem.php');

//problem1();
problem2();

function problem2()
{
    $problem = new SimplexProblem();

    $restriction = new SimplexRestriction();
    $restriction->addVar(new SimplexVariable(2, 'Pedreiros'));
    $restriction->addVar(new SimplexVariable(3, 'Serventes'));
    $restriction->setRestrictionValue(30);
    $restriction->setRestrictionType(0);
    $problem->addRestriction($restriction);

    $restriction = new SimplexRestriction();
    $restriction->addVar(new SimplexVariable(4, 'Pedreiros'));
    $restriction->addVar(new SimplexVariable(8, 'Serventes'));
    $restriction->setRestrictionValue(70);
    $restriction->setRestrictionType(0);
    $problem->addRestriction($restriction);

    $objectiveFunction = new SimplexObjectiveFunction("LUCRO");
    $objectiveFunction->addVar(new SimplexVariable(3000, 'Pedreiros'));
    $objectiveFunction->addVar(new SimplexVariable(5000, 'Serventes'));
    $problem->setObjectiveFunction($objectiveFunction);

    $problem->solve();
    echo $problem->getBestSolution();
}

function problem1(){
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
}








