<html>
<head>
    <meta charset='utf-8'>
    <title> EasyPlex </title>
    <script src="lib/jquery/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<div>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-background">
        <a class="navbar-brand nav-font-color" href="index.php">EasyPlex</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#problem-box">Problema <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#answer-box" id="solutionButton">Solução</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#analysis-box" id="analysis-button">Quadro de Análise</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#steps-box" id="steps-button">Passo a Passo</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div id="main-content" class="container-fluid">    
    <div id="problem-box" class="problem-box">        
        <div class="box-title">
            <span>Problema</span>
        </div>       
        <div class="box-outer-background">
            <div class="box-content">
                <div class="form-group">
                    <label class="label-title">Função Problema</label>
                    <div id="divProblemFunction" class="form-row">   
                        <div class="col col-md-2">  
                            <select id="selectFunctionType" class="form-control" name ="selectFunctionType">
                                <option name="selectFunctionType" value="max"> Maximizar </option> 
                                <option name="selectFunctionType" value="min"> Minimizar </option>               
                            </select>
                        </div>
                        <div class="col col-md-2">
                            <input type="text" id="inputObjectiveVar" class="form-control" placeholder="Exemplo: z"/>    
                        </div>
                        <div class="col">
                            <input type="text" id="inputObjectiveExp" class="form-control" placeholder="Exemplo: 3x + 2y"/>
                        </div>                
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="label-title">Restrições</label>
                    <div id="divRestrictions">            
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="button-resolve-space">
                <div class="form-group">
                    <div class="form-row">                    
                        <button onclick="resolve()" class="btn button-resolve"> Resolver </button>  
                        <label class="label-title" for="maxIterationsNum">Máximo de iterações: </label>
                        <input type="number" name="maxIterationsNum" id="maxIterationsNum" value="1000" class="form-control" style="width: 100px;"/>   
                    </div>  
                </div>        
            </div>
        </div>               
    </div>
    <div id="answer-box" class="answer-box">
        <div class="box-title">
            <span>Solução</span>
        </div>
        <div class="box-outer-background">
            <div class="box-content">
                <div id="solution-content">
                    
                </div>
            </div>
        </div>
    </div>
    <div id="analysis-box" class="answer-box">
        <div class="box-title">
            <span>Análise de Sensibilidade</span>
        </div>
        <div class="box-outer-background">
            <div class="box-content">
                <div id="analysis-content">
                    
                </div>
            </div>
        </div>
    </div>

    <div id="steps-box" class="answer-box">
        <div class="box-title">
            <span>Passo a Passo de Soluções</span>
        </div>
        <div class="box-outer-background">
            <div class="box-content">
                <div id="steps-content">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="simplex.js"></script>
</body>
</html>