<?php 

class Config {

    private static $config;
    private $values;

    private function __construct() {

        $conf = json_decode(file_get_contents("../config.json"), true);
        $url = $conf['production'] ? $conf['url_production'] : $conf['url_development'];
        $conf['url'] = $url[- 1] == "/" ? substr($url, 0, -1) : $url;
        $this->values = $conf;
        
    }

    private static function getConfig() {
        
        if(!self::$config)
            self::$config = new Config();

        return self::$config;
    }

    public static function getValues() {
        return self::getConfig()->values;
    }

} 