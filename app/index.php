<?php
/**
 *          [SIMPLE]
 *         develop by
 *         ██▀▀▀▀▀▀██
 *        █          █
 *       ██ ▄▀█  █▀▄ ██
 *       █            █
 *       █ ▀▄  ▄▄  ▄▀ █
 *       ██  ▀▀  ▀▀  ██
 *        ██▄  ▐▌  ▄██
 *          ▀▀▀▀▀▀▀▀
 *          [Δи̲̅ÐЯΞƗ]
 * 
 *   ┏━━━┓━━┳━━━━┓━━━┓━━┓━┓
 *   ┃ ┏┓┃ ┃┃┃ ┓┓┃┃━┓┃ ┳┛ ┃
 *   ┃ ┣┃┃ ┃┃┃ ┻┛┃┃━┓┃ ┻┓ ┃
 *   ┗━┛┗┛━┻━┛━━━┛━ ┗┗━━┛━┛
 *     andreicoelho.com.br   
 *                
 */

session_start();
require_once "../autoload.php";

$config = config();
$req    = request(["get" => [0 => 'file']])->get(0);

use SQLi\SQLi as sqli;

sqli::setDB('{
    "mydb1":{
        "driver":"mysql",
        "host":"botheat.mysql.dbaas.com.br",
        "dbname":"botheat",
        "user":"botheat",
        "pass":"MTcxNj741"
    }
}');

switch ($req) {
    case 'api':

        require_once "../helpers/api.php";
        $nonce = "auth";
        $fileName = request(["get" => [1 => 'file']])->get(1);

        if($fileName && file_exists(($file = "../api/".$fileName.".php"))){
            
            include $file;
            $func = request(["get" => [1 => 'file']])->get(2);
            $function = $fileName."_".$func;
            
            if(!function_exists($function)){
                api_response(); break;
            }

            $function();
            break;

        }
        api_response();

        break;
    
    case 'request':
        echo "solicitou request";
        break;

    case 'script':

        $file = request(["get" => [1 => 'file']])->get(1);
        if(!$file) {
            echo "Erro"; break;
        }
        $script = "../scripts/".$file.".php";
        if(!file_exists($script)) {
            echo "Erro 2"; break;
        }
        include $script;

        break;

    default:
        $file = $req == null ? $config['start'] : $req;
        page($file);
        break;
}