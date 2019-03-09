<html>
<head>
    <title> Simplex BMI </title>
</head>
<body>
<div>
    <div>
        <div>
            <select id="selectFunctionType" name ="selectFunctionType">
                <option name ="selectFunctionType" value="max"> Maximizar </option>                
            </select>
            <input type="text" id = "inputObjectiveVar" placeholder="LUCRO"/>  
            <input type="text" id = "inputObjectiveExp" placeholder="3x + 2y"/>
        </div>
        <div id="divRestrictions">            
        </div>
        <div>
            <button onclick="resolve()"> Resolver </button>
            <textarea type="text" id="answer"></textarea>
        </div>    
    </div>
</div>
<script>
    var nextRestrictionNumber = 1;
    addNewRestriction();

    function addNewRestriction()
    {
        var divAllRestrictions         = document.getElementById('divRestrictions');
        var divRestriction             = document.createElement('div');
        var inputRestrictionExpression = document.createElement('input');
        var selectRestrictionType      = document.createElement('select');
        var optionRestrictionType      = document.createElement('option');
        var inputRestrictionValue      = document.createElement('input');
        var buttonRestrictionAdd       = document.createElement('button');

        divRestriction.id = "restriction_" + nextRestrictionNumber;

        inputRestrictionExpression.id   = "inputRestrictionExpression" + nextRestrictionNumber;
        inputRestrictionExpression.type = "text";
        inputRestrictionExpression.placeholder = "5x + 4y";
        divRestriction.appendChild(inputRestrictionExpression);

        optionRestrictionType.innerHTML = " <= ";
        optionRestrictionType.id        = "optionRestrictionType" + nextRestrictionNumber;
        optionRestrictionType.name      = "selectRestrictionType" + nextRestrictionNumber;
        optionRestrictionType.value     = "<=";
        selectRestrictionType.id        = "selectRestrictionType" + nextRestrictionNumber;
        selectRestrictionType.name      = "selectRestrictionType" + nextRestrictionNumber;
        selectRestrictionType.add(optionRestrictionType);
        divRestriction.appendChild(selectRestrictionType);

        inputRestrictionValue.id   = "inputRestrictionValue" + nextRestrictionNumber;
        inputRestrictionValue.type = "number";
        inputRestrictionValue.placeholder = "18";
        divRestriction.appendChild(inputRestrictionValue);

        buttonRestrictionAdd.id        = 'buttonRestrictionAdd' + nextRestrictionNumber;
        buttonRestrictionAdd.innerHTML = ' + ';
        buttonRestrictionAdd.addEventListener('click', function (event){
            var buttonRestriction = event.target;

            if(buttonRestriction.innerHTML == ' + ')
            {
                addNewRestriction();
                buttonRestriction.innerHTML = ' - ';
            } else {
                var divAllRestrictions = document.getElementById('divRestrictions');
                divAllRestrictions.removeChild(buttonRestriction.parentElement);
            }
        });
        divRestriction.appendChild(buttonRestrictionAdd);

        divAllRestrictions.appendChild(divRestriction);
        nextRestrictionNumber++;
    }

    function resolve()
    {        
        var restrictions = document.getElementById('divRestrictions').children;
        var problem = {}
        try{
            problem.objective    = document.getElementById('selectFunctionType').value;
            problem.objectiveVar = document.getElementById('inputObjectiveVar').value;        
            problem.objectiveExp = parseExp(document.getElementById('inputObjectiveExp').value);

            problem.restrictions = [];

            for(var i=0; i<restrictions.length; i++){
                var restriction = {};
                var number = restrictions[i].id.split('_')[1];
                restriction.vars  = parseExp(document.getElementById('inputRestrictionExpression' + number).value);
                restriction.type  = document.getElementById('selectRestrictionType' + number).value;
                restriction.value = document.getElementById('inputRestrictionValue' + number).value;
                problem.restrictions.push(restriction);
            }            

            var request = JSON.stringify(problem);

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("answer").value = this.responseText;
                }
            };
            xhttp.open("POST", "simplexbmi.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send('problem='+request);
            
            
        } catch(msg){
            alert(msg);
        }         
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
                    } else if (char.match(/[a-zA-Z]/) != null){
                        state = 4;
                        varString += char;                    
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
                        flagInverter = true;                   

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
            throw "Final da expressão inesperado";
        } else {
            return vars;
        }    
    }
</script>
</body>
</html>