<?php 

namespace SQLi; 

class DataBase {
    
    private $pdo;
    private $alias, $driver, $host, $dbname, $user, $pass, $port, $charset;
    private $open = false;
	
	private $callback;

    public function __construct($alias, $driver, $host, $dbname, $user, $pass, $charset, $port, $callback){
        
        $this->alias    = $alias;
        $this->driver   = $driver;
        $this->host     = $host;
        $this->dbname   = $dbname;
        $this->user     = $user;
        $this->pass     = $pass;
        $this->charset  = $charset;
        $this->port     = $port;
        $this->callback = $callback;
    
    }

    public function get(){
        
        if(!$this->open){
        
            $strConn = $this->driver.":host=".$this->host.";";
            if($this->port) $strConn .= "port=".$this->port;
            $strConn .= "dbname=".$this->dbname.";";
            $strConn .= "charset=".$this->charset;

            try {
                $this->pdo = new \PDO($strConn, $this->user, $this->pass);
            } catch (\PDOException $e){
                if(($call = $this->callback) !== null){
                    $call($e, $this);
                }
            }

            $this->open = true;

        }
        return $this->pdo;
    }
	
    public function close(){
        $this->pdo = null;
    }

    // getters

    public function host(){
        return $this -> host;
    }

    public function dbname(){
        return $this -> dbname;
    }

    public function alias(){
        return $this -> alias;
    }

}
