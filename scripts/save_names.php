<?php 

use SQLi\SQLi as sqli;

# insere nomes

$nomes = explode(" ", str_replace("VM1444:1", "", file_get_contents("../nomes.txt")));

foreach($nomes as $nome){
    $n = trim($nome);
    if($n != ""){
        sqli::exec("INSERT INTO nomes (nome, sexo) VALUES ('$n', 'F')");
    }
}

# insere sobrenomes

$sobrenomes = explode(",", str_replace("\"", "", file_get_contents("../sobrenomes.txt")));
foreach ($sobrenomes as $value) {
    $n = trim($value);
    if($n != ""){
        sqli::exec("INSERT INTO sobrenomes (sobrenome) VALUES ('$n')");
    }
}