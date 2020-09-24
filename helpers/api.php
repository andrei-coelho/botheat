<?php 

function api_response($response = false, bool $status = true) {
    header("Content-Type: application/json");
    $obj['status']   = (!$response ? false : $status);
    $obj['response'] = (!$response ? "Error 404" : $response); 
    echo json_encode($obj);
}

function nonce(){

}