var nextItemNumber = 1;
addNewItem();
$('#answer-box').hide();
$('#solutionButton').hide();

function addNewItem()
{
    var html = '';

    html += "<div id='item_" + nextItemNumber + "' class='form-row'>";
    
    html += "<div class='col col-lg-4'>";
    html += "<input id='inputItemName" + nextItemNumber + "' type='text' placeholder='Exemplo: Relógio' class='form-control'/>";
    html += "</div>";

    html += "<div class='col col-lg-3'>";
    html += "<input id='inputItemValue" + nextItemNumber + "' type='number' placeholder='Valor: 34' class='form-control'/>";
    html += "</div>";

    html += "<div class='col col-lg-3'>";
    html += "<input id='inputItemWeight" + nextItemNumber + "' type='number' placeholder='Peso: 50' class='form-control'/>";
    html += "</div>";

    html += "<div class='col col-md-1'>";
    html += "<button id='buttonItemAdd" + nextItemNumber + "' class='btn btn-theme-dark'> + </button>"
    html += '</div>';

    html += "</div>";

    $('#divBag').append(html);    
    
    $('#buttonItemAdd' + nextItemNumber).on('click', function(event){
        var buttonRestriction = event.target;
        $('#solution-content').html('<span>Descreva um problema acima e clique no botão resolver.</span>');
        $('#answer-box').hide();
        $('#solutionButton').hide();
        if(buttonRestriction.innerHTML == ' + ')
        {
            var items = $('[id^="inputItemValue"], [id^="inputItemName"], [id^="inputItemWeight"]');
            var continuar = true;

            for(var i=0; i<items.length; i++){
                if(items[i].value == ''){
                    continuar = false;
                    break;
                }
            }

            if(continuar == true){
                addNewItem();
                buttonRestriction.innerHTML = ' - ';
            } else {
                alert('Por favor. Termine de digitar os dados do item antes de inserir um novo.');
            }
            
        } else {
            var divAllItems = document.getElementById('divBag');
            divAllItems.removeChild(buttonRestriction.parentElement.parentElement);
        }
    });
    nextItemNumber++;
}

function resolve()
{
    $('#answer-box').hide();
    $('#solutionButton').hide();

    try{
        var items = [];
        var namesList = [];
        for(var i=0; i<nextItemNumber; i++){
            var name   = $('#inputItemName'   + i);
            var weight = $('#inputItemWeight' + i);
            var value  = $('#inputItemValue'  + i);

            if(name.length > 0){
                var item = {};
                item.name   = name.val();
                item.weight = weight.val();
                item.value  = value.val();
                
                items.push(item);

                if (namesList.includes(item.name)){
                    throw "Por favor, não insira dois itens com o mesmo nome.";
                }
                namesList.push(item.name);
            }
        }


        var request = JSON.stringify(items);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {                  
                
                writeAnswer(this.responseText);
                window.location.assign("#answer-box");
            }
        };
        xhttp.open("POST", "easybag.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('problem='+request + '&maxWeight=' + $('#maxWeightValue').val());
    
    } catch (exc){
        alert(exc);
    }
}

function writeAnswer(answerString){
    $('#solution-content').html('');
    var answer = JSON.parse(answerString);

    // verifica se nao deu excecao
    if(answer.type == 1){
        writeSolution(answer.message);
    } else if (answer.type == 2){
        writeException(answer.message);
    }        
}

function writeException(message){
    $('#solution-content').append('<strong> '+message+'</strong>');
    $('#answer-box').show();
}

function writeSolution(message){
    
    var html = '';
    html += '<strong>' + message.bestSolution + '</strong>' ;
    
    $('#solution-content').html(html);
    $('#answer-box').show();
    $('#solutionButton').show();
}
