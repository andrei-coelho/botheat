<?php 
/**
 * 
 *           develop by     
 *     ┏━━━┓━━┳━━━━┓━━━┓━━┓━┓
 *     ┃ ┏┓┃ ┃┃┃ ┓┓┃┃━┓┃ ┳┛ ┃
 *     ┃ ┣┃┃ ┃┃┃ ┻┛┃┃━┓┃ ┻┓ ┃
 *     ┗━┛┗┛━┻━┛━━━┛━ ┗┗━━┛━┛
 *       andreicoelho.com.br   
 * 
 *       [HELPER FOR SOURCE]
 */

function request(array $filter = [], bool $verify = false){
    return !$filter ? Request::getRequest() : Request::filter($filter, $verify);
}

function config(){
    return Config::getValues();
}

function page($page){
    
    $file = "../pages/".$page.".php";
    
    if(file_exists($file)) {    
        include $file;
        return;
    }
        
    $file = Route::getFileIfExists();
    if(!$file) {
        include "../pages/error404.php";
        return;
    }
    
    $file = "../pages/".$file.".php";
    if(file_exists($file)) {
        include $file;
        return;
    }

    include "../pages/error404.php";

}

function verify_page($page){

}

function route(string $route, string $file){
    Route::add($route, $file);
}

function img(string $file){
    echo config()['url']."/public/img/".$file;
}

function js(string $file){
    echo config()['url']."/public/js/".$file.".js";
}