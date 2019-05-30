<html>
<head>
    <meta charset='utf-8'>
    <title> EasyBag </title>
    <script src="lib/jquery/jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<div>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top nav-background">
        <a class="navbar-brand nav-font-color" href="mochila.php">EasyBag</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#bag-problem-box">Problema <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#answer-box" id="solutionButton">Solução</a>
                </li>
            </ul>
        </div>
        <div>
            <a href="index.php" style="color: black;">Ir para o EasyPlex</a>
        </div>
    </nav>
</div>
<div id="main-content" class="container-fluid">    
    <div id="bag-problem-box" class="problem-box">        
        <div class="box-title">
            <span>Problema da Mochila</span>
        </div>       
        <div class="box-outer-background">
            <div class="box-content">
                <div class="form-group">
                    <label class="label-title">Descrição dos possíveis itens </label>
                    <div id="divBag">            

                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="button-resolve-space">
                <div class="form-group">
                    <div class="form-row">                    
                        <button onclick="resolve()" class="btn button-resolve"> Resolver </button> 
                        <label class="label-title" for="maxWeightValue">Peso máximo da mochila </label>
                        <input type="number" name="maxWeightValue" id="maxWeightValue" value="15" class="form-control" style="width: 100px;"/>                            
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
</div>
<script type="text/javascript" src="lib/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="mochila.js"></script>
</body>
</html>