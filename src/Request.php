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
 */

class Request {


    private static $request;
    private static $status = true;
    
    private $gets  = [];
    private $posts = [];

    private $route;

    private function __construct(){

        if(isset($_GET['req'])){
            
            $this->route = $_GET['req'];
            $this->gets  = explode('/', $_GET['req']);
            $gets = $this->gets;

            foreach ($gets as $key => $value) {

                if(strpos($value, "=") !== false){

                    $parts = explode("|", $value);

                    foreach ($parts as $k => $v) {
                        $vars = explode("=", $v);
                        $this->gets[$vars[0]] = $vars[1];
                    }
                }

            }

        }

        foreach ($_POST as $key => $value) {
            $this->posts[$key] = $value;
        }

    }


    public function get($key = false){

        return $key !== false ? 
            isset($this->gets[$key]) ? $this->gets[$key] : false
        : $this->gets;

    }


    public function post($key = false){
        return $key !== false ? 
            isset($this->posts[$key]) ? $this->posts[$key] : false
        : $this->posts;
    }


    public static function filter(array $filter = [], bool $verify){

        $request = self::getRequest();

        if(isset($filter['get']) && is_array($filter['get'])){

            foreach ($filter['get'] as $k => $v) {
                if(isset($request->gets[$k]) && method_exists($request, $v)){
                    $request->gets[$k] = self::$v($request->gets[$k]);
                }
            }

        }

        return $request;

    }


    public static function getRequest(){

        if(!self::$request) 
            self::$request = new Request();
        return self::$request;

    }


    public static function getRoute(){

        return self::getRequest()->route;

    }


    // magic methods


    private static function slug($val){

        $val = preg_replace('~[^\pL\d]+~u', '-', $val); // substitui por -
        $val = iconv('utf-8', 'us-ascii//TRANSLIT', $val);
        $val = preg_replace('~[^-\w]+~', '', $val);
        $val = trim($val, '-');
        $val = preg_replace('~-+~', '-', $val);
        return $val;

    }


    private static function file($val){

        $val = preg_replace('~[^\pL\d]+~u', '_', $val); // substitui por _
        $val = iconv('utf-8', 'us-ascii//TRANSLIT', $val);
        $val = preg_replace('~[^-\w]+~', '', $val);
        $val = trim($val, '-');
        $val = preg_replace('~-+~', '-', $val);
        return $val;

    }


    private static function int($val){
        return (int)$val;
    }

}