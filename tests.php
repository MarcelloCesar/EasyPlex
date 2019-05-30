<?php

require_once('simplexProblem.php');
require_once('knapsackproblem.php');
ini_set('display_errors', 'on');
//problem1();
//problem2();

problem3();
function problem3()
{
    //knapsack

    $problem = new KnapsackProblem();

    $problem->addItem(new KnapsackItem('carro', 3, 5));
    $problem->addItem(new KnapsackItem('costas', 4, 2));
    $problem->addItem(new KnapsackItem('passos', 2, 56));
    $problem->addItem(new KnapsackItem('casa', 2, 56));
    $problem->addItem(new KnapsackItem('anjo', 1, 32));
    $problem->addItem(new KnapsackItem('luta', 2, 22123123));
    $problem->addItem(new KnapsackItem('luana', 1,35));
    $problem->addItem(new KnapsackItem('passo3s', 52, 123231));

    $answer = array();

    foreach($problem->solve(2)[1] as $solution){
        $answer[] = $problem->getItemAt($solution)->getName();
    }

    echo implode(", ", $answer);
}

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








