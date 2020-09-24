<?php 

class Route {

    private static $list = [];
    
    private $route, $file;
    private $included = false;

    private function __construct($route,  $file){
        $this->route = $route;
        $this->file  = $file;
    }

    public static function add(string $route, string $file){
        self::$list[] = new Route($route, $file);
    }

    public static function getFileIfExists(){

        $route = request()->getRoute();

        foreach (self::$list as $r) {
            if(!$r -> included){
                $regex = str_replace("/", "\/", $r -> route);
                if(preg_match('/'.$regex.'/', $route)){
                    $r -> included = true;
                    return $r -> file;
                }
            }
            
        }

        return false;

    }

}