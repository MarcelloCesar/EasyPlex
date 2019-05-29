<?php
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    header('location: mochila.php');
}
require_once('knapsackproblem.php');
$request = json_decode($_POST['problem']);

try{
    
    $problem = new KnapsackProblem();
    
    foreach($request as $item){
        $problem->addItem(
            new KnapsackItem(
                $item->name,
                $item->weight,
                $item->value
            )
        );
    }
    $problem->solve($_POST['maxWeight']);

    $response = array();
    $response['type']    = 1;
    $response['message'] = array();
    $response['message']['bestSolution'] = geraResposta($problem);
    echo json_encode($response);

} catch (Exception $exc){  
    $response = array();
    $response['type'] = 2;
    $response['message'] = $exc->getMessage();

    echo json_encode($response);
}

function geraResposta($problema){
    $msgResposta = '';

    if($problema->getBestPath() == null){
        $msgResposta = 'Itens a serem carregados: Nenhum!';
    } else {
        $msgResposta = 'Itens a serem carregados: '  . $problema->getBestPath() 
                       . '.<br/> Valor carregado: '  . $problema->getBestValue()
                       . '.<br/> Peso carregado: '   . $problema->getBestWeight().'.';
    }

    return $msgResposta;
}