<?php 

use SQLi\SQLi as sqli;

function perfis_salvarPerfil(){
    
    $values = request()->post();
    $campos = "(".implode(",", array_keys($values)).") ";
    $valor  = "VALUES ('".implode("','", array_values($values))."'); ";

    $insert = "INSERT INTO perfis ".$campos.$valor;
    $status = sqli::exec($insert);
    
    api_response(($status == true ? "ok" : $status));
    
}

function perfis_gerarPerfil(){

    $response = [];
    $nomeSel = sqli::query("SELECT nome FROM nomes ORDER BY rand() LIMIT 1");
    $sobrenomeSel = sqli::query("SELECT sobrenome FROM sobrenomes ORDER BY rand() LIMIT 1");
    $imageSel = sqli::query("SELECT id, slug FROM imagens_perfil ORDER BY id ASC LIMIT 1");
    $image = $imageSel ->fetchAssoc();
    // sqli::exec("UPDATE imagens_perfil SET used = 1 WHERE id = ".$image['id']);

    $response['nome']      = trim(mb_ucfirst(mb_strtolower($nomeSel->fetchAssoc()['nome'], 'utf-8')));
    $response['sobrenome'] = trim(mb_ucfirst(mb_strtolower($sobrenomeSel->fetchAssoc()['sobrenome'], 'utf-8')));
    $response['senha']     = generate_pass();
    $response['email']     = generate_email($response['nome'], $response['sobrenome'])."@outlook.com";
    $response['data']      = generate_data();
    $response['imagem']    = config()['url']."/public/img/profiles/".$image['slug'];
    $response['imagem_id'] = $image['id'];

    api_response($response);

}


function generate_data(){

    $time = mt_rand(strtotime("01/01/1988"), strtotime("01/01/1994"));
    return date("d/m/Y", $time);

}

function generate_email($nome, $sobrenome){

    $val = $nome.$sobrenome;
    $val = preg_replace('~[^\pL\d]+~u', '_', $val); // substitui por _
    $val = iconv('utf-8', 'us-ascii//TRANSLIT', $val);
    $val = preg_replace('~[^-\w]+~', '', $val);
    $val = trim($val, '-');
    $val = preg_replace('~-+~', '-', $val);
    $val = mb_strtolower($val);
    return $val;

}

function generate_pass(){
    
    $letters  = "abcdefgh1jklmnopqrstuvxyz";
    $maiusc   = "ABCDEFGHIJKLMNOPQRSTUVXYZ";
    $simbolos = "@#$&*><?:+=!";
    $numeros  = "1234567890";

    $maxL = strlen($letters)-1;
    $maxM = strlen($maiusc)-1;
    $maxS = strlen($simbolos)-1;
    $maxN = strlen($numeros)-1;

    $lL = $mL = $sL = $nL = [];

    #pegando os indices das letras
    $total = 3;
    while(true){
        $n = mt_rand(0, $maxL);
        if(!in_array($n, $lL)) $lL[] = $n;
        if(count($lL) == $total) break;
    }

    #pegando os indices das maiusculas
    $total = 2;
    while(true){
        $n = mt_rand(0, $maxM);
        if(!in_array($n, $mL)) $mL[] = $n;
        if(count($mL) == $total) break;
    }

    #pegando os indices dos numeros
    $total = 2;
    while(true){
        $n = mt_rand(0, $maxN);
        if(!in_array($n, $nL)) $nL[] = $n;
        if(count($nL) == $total) break;
    }

    #pegando o indice do simbolo
    $sL[] = mt_rand(0, $maxS);

    // [2l][1m][1s][1m][1n][1l][1n]
    return  $letters  [$lL[0]]
           .$letters  [$lL[2]]
           .$maiusc   [$mL[0]]
           .$simbolos [$sL[0]]
           .$maiusc   [$mL[1]]
           .$numeros  [$nL[0]]
           .$letters  [$lL[1]]
           .$numeros  [$nL[1]]
    ;

}

function mb_ucfirst( $str, $encoding = 'utf-8' ) {

    $use_mb = function_exists( 'mb_convert_encoding' );
    if ( $use_mb ) {
        return mb_strtoupper( mb_substr( $str, 0, 1, $encoding ), $encoding ) . mb_substr( $str, 1, mb_strlen( $str, $encoding ), $encoding );
    } else {
        return ucfirst( $str );
    } 

}