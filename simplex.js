var nextRestrictionNumber = 1;
addNewRestriction();
$('#inputObjectiveVar').focus();

$('#answer-box').hide();
$('#analysis-box').hide();
$('#steps-box').hide();

$('#solutionButton').hide();
$('#analysis-button').hide();
$('#steps-button').hide();

function addNewRestriction()
{    
    var divAllRestrictions         = document.getElementById('divRestrictions');
    var divRestriction             = document.createElement('div');
    var inputRestrictionExpression = document.createElement('input');
    var selectRestrictionType      = document.createElement('select');
    var optionRestrictionType      = document.createElement('option');
    var inputRestrictionValue      = document.createElement('input');
    var buttonRestrictionAdd       = document.createElement('button');
    var divCol;

    /*
    
    $('#divRestrictions').append(
        "<div class='form-row' id='restriction_" + nextRestrictionNumber + "'>" + 
            "<div class='col'>" + 
                "<input type='text' id='inputRestrictionExpression" + nextRestrictionNumber + "' placeholder ='5x + 4y' class='form-control' />" +
            "</div>" + 
            "<div class='col'>"+
                "<option id='optionRestrictionType" + nextRestrictionNumber + "' id='optionRestrictionType" + nextRestrictionNumber><=</option>"
            "</div>" +
        "</div>"
    );        */
    
    divRestriction.id = "restriction_" + nextRestrictionNumber;
    divRestriction.classList.add("form-row");

    inputRestrictionExpression.id   = "inputRestrictionExpression" + nextRestrictionNumber;
    inputRestrictionExpression.type = "text";
    inputRestrictionExpression.placeholder = "Exemplo: 5x + 4y";
    inputRestrictionExpression.classList.add("form-control");
    divCol = document.createElement('div');
    divCol.classList.add('col');
    divCol.classList.add('col-lg-6');
    divCol.classList.add('restriction-expr');
    divCol.appendChild(inputRestrictionExpression);
    divRestriction.appendChild(divCol);

    optionRestrictionType.innerHTML = " <= ";
    optionRestrictionType.id        = "optionRestrictionType" + nextRestrictionNumber;
    optionRestrictionType.name      = "selectRestrictionType" + nextRestrictionNumber;
    optionRestrictionType.value     = "<=";
    selectRestrictionType.tabIndex  = -1;
    selectRestrictionType.id        = "selectRestrictionType" + nextRestrictionNumber;
    selectRestrictionType.name      = "selectRestrictionType" + nextRestrictionNumber;
    selectRestrictionType.classList.add("form-control");
    selectRestrictionType.add(optionRestrictionType);
    divCol = document.createElement('div');
    divCol.classList.add('col');  
    divCol.classList.add('restriction-type');  
    divCol.appendChild(selectRestrictionType);
    divRestriction.appendChild(divCol);

    inputRestrictionValue.id   = "inputRestrictionValue" + nextRestrictionNumber;
    inputRestrictionValue.type = "number";
    inputRestrictionValue.placeholder = "Exemplo: 18";
    inputRestrictionValue.classList.add("form-control");
    divCol = document.createElement('div');
    divCol.classList.add('col');     
    divCol.classList.add('col-lg-3');       
    divCol.classList.add('restriction-value');       
    divCol.appendChild(inputRestrictionValue);
    divRestriction.appendChild(divCol);

    buttonRestrictionAdd.id        = 'buttonRestrictionAdd' + nextRestrictionNumber;
    buttonRestrictionAdd.innerHTML = ' + ';
    buttonRestrictionAdd.classList.add('btn-theme-dark');
    buttonRestrictionAdd.classList.add('btn');
    buttonRestrictionAdd.addEventListener('click', function (event){
        var buttonRestriction = event.target;
        $('#solution-content').html('<span>Descreva um problema acima e clique no botão resolver.</span>');
        $('#answer-box').hide();
        $('#analysis-box').hide();
        $('#steps-box').hide();
        $('#solutionButton').hide();
        $('#analysis-button').hide();
        $('#steps-button').hide();
        if(buttonRestriction.innerHTML == ' + ')
        {
            addNewRestriction();
            buttonRestriction.innerHTML = ' - ';
        } else {
            var divAllRestrictions = document.getElementById('divRestrictions');
            divAllRestrictions.removeChild(buttonRestriction.parentElement.parentElement);
        }
    });

    divCol = document.createElement('div');
    divCol.classList.add('col');   
    divCol.classList.add('col-md-1');          
    divCol.appendChild(buttonRestrictionAdd);
    divRestriction.appendChild(divCol);

    divAllRestrictions.appendChild(divRestriction);
    nextRestrictionNumber++;
}

function resolve()
{        
    $('#answer-box').hide();
    $('#analysis-box').hide();
    $('#steps-box').hide();
    $('#solutionButton').hide();
    $('#analysis-button').hide();
    $('#steps-button').hide();

    //Remover a div do servidor
    // setTimeout(() => {
    //     var divs = $('body > div');
    //     var lastDiv = divs[divs.length -1];  
    //     if(lastDiv.style == 'text-align: right;position: fixed;z-index:9999999;bottom: 0;width: auto;right: 1%;cursor: pointer;line-height: 0;display:block !important;')
    //         divs[divs.length -1].remove();
    // }, 10000);

    var restrictions = document.getElementById('divRestrictions').children;
    var problem = {}
    try{

        if($('#inputObjectiveVar').val() == '' || $('#inputObjectiveExp').val() == ''){
            throw "Por favor, digite os dados da funÃ§Ã£o problema.";
        }

        // fazer validacao melhor ja q pode adicionar e remover estricao, n vai necesariamente ser a 2
        if(nextRestrictionNumber = 2 && ($('#inputRestrictionExpression1').val() == '' || $('#inputRestrictionValue').val() == '')) {
            throw "Por favor, digite os dados da restriÃ§Ã£o." ;
        }
        
        problem.objective    = document.getElementById('selectFunctionType').value;
        problem.objectiveVar = document.getElementById('inputObjectiveVar').value;        
        problem.objectiveExp = parseExp(document.getElementById('inputObjectiveExp').value);

        problem.restrictions = [];

        for(var i=0; i<restrictions.length; i++){
            var restriction = {};
            var number = restrictions[i].id.split('_')[1];
            if($('#inputRestrictionExpression' + number).val() != ''){ 
                restriction.vars  = parseExp(document.getElementById('inputRestrictionExpression' + number).value);
                restriction.type  = document.getElementById('selectRestrictionType' + number).value;
                restriction.value = document.getElementById('inputRestrictionValue' + number).value;
                problem.restrictions.push(restriction);
            }
        }            

        var request = JSON.stringify(problem);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {                  
                
                writeAnswer(this.responseText);
                window.location.assign("#answer-box");
            }
        };
        xhttp.open("POST", "easyplex.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('problem='+request + '&maxIterations=' + $('#maxIterationsNum').val());
        
        
    } catch(msg){
        alert(msg);
    }         
}

function writeAnswer(answerString){
    $('#solution-content').html('');
    var answer = JSON.parse(answerString);

    // verifica se nao deu excecao
    if(answer.type == 1){
        writeSolution(answer.message);
        writeAnalysis(answer.message.analysisBoard);
        writeSteps(answer.message.otherSolutions);
    } else if (answer.type == 2){
        writeException(answer.message);
    }        

}

function writeException(message){
    $('#solution-content').append('<strong> '+message+'</strong>');
    $('#answer-box').show();
}

function writeSolution(message){    
    // Escreve a solucao resumida
    var html = '<label class="label-title" for="maxIterationsNum">Solução Ótima: </label><br/>';
    for(var i in message.bestSolution){
        html += '<strong>' + i + '</strong>' + ': ' + message.bestSolution[i] + '<br>';
    }

    // Escreve a tabela solucao
    html +="<hr><label class='label-title' for='tableSolution'>Tabela Solução: </label></br></br>";
    html +="<div style='overflow:auto'>";
    html +="<table class='table table-hover' id='tableSolution'>";
    // Adicao das colunas da tabela 
    var lastSolution = message.otherSolutions[message.otherSolutions.length-1];        
    html += "<thead class='thead-light'><tr>";
    for(var i in lastSolution){
        html += "<th scope='col'>" + i + "</th>";
    }
    html += "</tr></thead>";
    html += "<tbody>";
    for(var i = 0; i < lastSolution.base.length; i++){
        html += "<tr>";
        html += "<th scope='row'>" + lastSolution.base[i] + "</th>";
            for(var j in lastSolution){
                if(j == 'base'){
                    continue;
                }
                html += "<td>"+lastSolution[j][i] + "</td>";      
            }
        html += "</tr>";
    }
    html += "</tbody>";
    html += "</table>";
    html += "</div>";

    $('#solution-content').html(html);
    $('#answer-box').show();
    $('#solutionButton').show();
}

function writeAnalysis(board)
{
    casa = board;
    var html = '';
    html +="<div style='overflow:auto'>";
    html +="<table class='table table-hover' id='analysisBoard'>";
    html += "<thead class='thead-light'><tr>";
    for(var i in board){        
        html += "<th scope='col'>" + i + "</th>";
    }
    html += "</tr></thead>";
    html += "<tbody>";
    for(var i = 0; i < board['Variável'].length; i++){
        html += "<tr>";
        html += "<th scope='row'>" + board['Variável'][i] + "</th>";
            for(var j in board){
                if(j == 'Variável'){
                    continue;
                }
                html += "<td>"+board[j][i] + "</td>";      
            }
        html += "</tr>";
    }
    html += "</tbody>";
    html += "</table>";
    html += "</div>";

    $('#analysis-content').html(html);
    $('#analysis-box').show();
    $('#analysis-button').show();
}

function writeSteps(solutions)
{
    var html = '';
    html += '<div id="steps-carousel" class="carousel slide" data-ride="carousel" data-interval="false">';
    html += '<div class="carousel-inner">';
    for(var aux=0; aux<solutions.length; aux++){        
        html += "<div class='carousel-item' id='solution" + aux + "'>";
        html +="<label class='label-title' for='tableSolution'>Solução "+ aux + ": </label></br></br>";
        html +="<div style='overflow:auto'>";
        html +="<table class='table table-hover' id='tableSolution'>";
        // Adicao das colunas da tabela 
        var lastSolution = solutions[aux];
        html += "<thead class='thead-light'><tr>";
        for(var i in lastSolution){
            html += "<th scope='col'>" + i + "</th>";
        }
        html += "</tr></thead>";
        html += "<tbody>";
        for(var i = 0; i < lastSolution.base.length; i++){
            html += "<tr>";
            html += "<th scope='row'>" + lastSolution.base[i] + "</th>";
                for(var j in lastSolution){
                    if(j == 'base'){
                        continue;
                    }
                    html += "<td>"+lastSolution[j][i] + "</td>";      
                }
            html += "</tr>";
        }
        html += "</tbody>";
        html += "</table>";
        html += "</div>";
        html += "</div>";        
    }
    html += '</div>';

    html += '<a class="carousel-control-prev" href="#steps-carousel" role="button" data-slide="prev">';
    html += '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
    html += '<span class="sr-only">Previous</span>';
    html += '</a>';

    html += '<a class="carousel-control-next" href="#steps-carousel" role="button" data-slide="next">';
    html += '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
    html += '<span class="sr-only">Next</span>';
    html += '</a>';

    html += '</div>';

    $('#steps-content').html(html);
    $('#solution0').addClass('active');
    $('#steps-box').show();
    $('#steps-button').show();
}

function parseExp(inputString)
{        
    var state = 0;
    var valid = false;
    var varString = '';
    var varNumber = '';
    var flagInverter = false;        

    var vars = [];
    var varAtual = [];

    for(var i = 0; i<inputString.length; i++)
    {
        var char = inputString[i];
        if (char ===' ')
            continue;   
        
        var exc = "Caracter " + char + " inesperado na posição " + i;

        switch(state)
        {
            case 0:
                valid = false;  
                flagInverter = false;
                varString = '';                  
                varNumber = '';
                if(char === '+') {
                    state = 1;
                } else if (char === '-') {
                    state = 3;
                } else if (char.match(/[0-9]/) != null){
                    varNumber += char;
                    state = 2;
                    flagInverter = false;                 
                } else if (char.match(/[a-zA-Z]/)){
                    varString += char;
                    state = 5;
                    valid = true;

                    varAtual = [];
                    varAtual = [parseInt(flagInverter ? (varNumber == '' ? '-1' : ('-' + varNumber)) : (varNumber == '' ? '1' : varNumber)),
                                varString
                    ]
                    vars.push(varAtual);                  
                } else {
                    throw exc
                }

                break;                
            case 1:
                valid = false;
                flagInverter = false;
                varString = ''; 
                if(char.match(/[0-9]/)){
                    varNumber += char;
                    state = 2;
                    flagInverter = false;                     
                } else if (char.match(/[a-zA-Z]/) != null){
                    state = 5;
                    valid = true;                        
                    varString += char; 
                    //flagInverter = true;                   
                    //Foi preciso remover esta inversao pois estava causando um bug em -x1+x2, tornando x2 negativo
                    // Ver se no futuro retirar esta inversao causara outros bugs ou se ficou certo

                    varAtual = [];
                    varAtual = [parseInt(flagInverter ? (varNumber == '' ? '-1' : ('-' + varNumber)) : (varNumber == '' ? '1' : varNumber)),
                                varString
                    ]
                    vars.push(varAtual);
                } else {
                    throw exc;
                }
                break;

            case 2:
                valid = false;
                varString = '';                    
                if (char.match(/[0-9]/)){
                    state = 2;
                    varNumber += char;
                } else if (char.match(/[a-zA-Z]/)){
                    varString += char;
                    state = 5;
                    valid = true;

                    varAtual = [];
                    varAtual = [parseInt(flagInverter ? (varNumber == '' ? '-1' : ('-' + varNumber)) : (varNumber == '' ? '1' : varNumber)),
                                varString
                    ]
                    vars.push(varAtual);
                } else {
                    throw exc;
                }
                break;

            case 3:
                valid = false;  
                varString = '';                  
                varNumber = '';               
                
                if (char.match(/[0-9]/) != null){
                    varNumber += char;                        
                    state = 2;
                    flagInverter = true;
                } else if (char.match(/[a-zA-Z]/) != null){
                    state = 5;
                    valid = true;                        
                    varString += char; 
                    flagInverter = true;                   

                    varAtual = [];
                    varAtual = [parseInt(flagInverter ? (varNumber == '' ? '-1' : ('-' + varNumber)) : (varNumber == '' ? '1' : varNumber)),
                                varString
                    ]
                    vars.push(varAtual);
                } else {
                    throw exc
                }

                break;

            case 5:

                if(char.match(/[0-9a-zA-Z]/)){
                    varAtual[1] += char;                                               
                } else if (char === '+'){
                    varAtual = [];
                    varNumber = '';
                    flagInverter = false;
                    varString = '';
                    state = 1;
                    valid = false;
                } else if(char === '-'){
                    varAtual = [];
                    varNumber = '';
                    flagInverter = true;
                    varString = '';
                    state = 3;
                    valid = false;
                } else {
                    throw exc;
                }
            
                break;
        }
    }

    if(!valid)
    {
        throw "Expressão inválida. Por favor verifique os campos informados.";
    } else {
        return vars;
    }    
}

function newProblem(){
    var newProblem = {};

    newProblem.objective = 1;
    newProblem.objectiveFunction = "";
    newProblem.restrictions = [];

    return newProblem;
}

function newRestriction(){
    var newRestriction = {};

    newRestriction.expression = "";
    newRestriction.type  = 1;
    newRestriction.value = undefined;

    return newRestriction;
}
