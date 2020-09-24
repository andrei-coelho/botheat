<?php

namespace SQLi;

use SQLi\DataBase as DataBase;
use SQLi\SQLiException as SQLiException;
use SQLi\Result as Result;

class SQLi {

	private static $databases = [];
	private static $lasterror = false;

	/**
	 *  clonagem e construtor estão impossiblitados para uso
	 */
	private function __construct() {}
	private function __clone() {}

	/**
	 *  Add new Data Base
	 */
	public static function addDB(
		string $alias, 
		string $driver, 
		string $host, 
		string $dbname, 
		string $user, 
		string $pass, 
		string $charset, 
		$port,
		$callback
	){
		self::$databases[$alias] = new DataBase(
			$alias,
			$driver, 
			$host, 
			$dbname, 
			$user, 
			$pass,
			$charset,			
			$port,
			$callback
		);

	}
	
	/**
	 *  Add new Data Bases using JSON
	 */
	public static function setDB(string $json, callable $callback = null){

		$json = json_decode($json, true);
		if(!$json) throw new SQLiException(1);
		
		foreach ($json as $alias => $values) {

			$port = isset($values['port']) ? $values['port'] : null;
			$charset = isset($values['charset']) ? $values['charset'] : "utf8";
			self::hasKeys(array_keys($values));
			self::addDB(
				$alias, 
				$values['driver'], 
				$values['host'], 
				$values['dbname'], 
				$values['user'],
				$values['pass'], 
				$charset,
				$port,
				$callback
			);

		}
		
	}

	public static function getDB(string $aliasDB = ""){

		if(count(self::$databases) === 0) throw new SQLiException(3);
		$key = $aliasDB === "" ? array_keys(self::$databases)[0] : $aliasDB;
		if(!isset(self::$databases[$key])) throw new SQLiException(4, $key);

		return self::$databases[$key];
	 
	}

	public static function getLastError(){
		return self::$lasterror;
	}

	/**
	 *  Using selects in Data Base
	 *  @param $query string
	 *  @param $values array
	 *  @param $aliasDB string - Use this if you need select in other data base 
	 */	
	public function query(string $query, array $values = [], string $aliasDB = ""){
		
		if(($pdo = self::getDB($aliasDB)->get()) === null) return false;
	
		$st = $pdo->prepare($query);
		if(!$st) throw new SQLiException(0, $pdo->errorInfo()[2]);
			
		if(count($values) > 1)
			self::setBinds($st, $values);
		
		$result = new Result($st);
		if($result->hasError()){
			self::$lasterror = $result->getCode();
			return false;
		}

		return  $result;
		
	}

	/**
	 *  Using this function for simple insert new row
	 *	In case of success this function will return true. 
	 *	In each error, this function will return the error code 
	 *  @param $insert string
	 *  @param $values array - ['ssi', 'value1', 'value2']
	 *  @param $aliasDB string - Use this if you need insert in other data base 
	 *  @param $pdo - for inner context
	 */	
	public static function insert(string $insert, array $values, string $aliasDB = "", $pdo = false){
		
		if(count($values) < 2) throw new SQLiException(5); 
		if(!$pdo && ($database = self::getDB($aliasDB)) !== false){
			$pdo = $database->get();
		}
		
		if($pdo === null) return false;
		
		$binds   = array_shift($values);
		$insert  = "INSERT INTO ".trim($insert)." VALUES (";
		$insert .= substr(str_repeat("?,", count($values)), 0, -1).")";
		
		$st = $pdo->prepare($insert);
		if(!$st) throw new SQLiException(0, $pdo->errorInfo()[2]);
		
		for($y = 1, $i = 0; $i < count($values); $i++, $y++){
			$var = &$values[$i];
			self::bind($st, $var, $y, $binds[$i]);
		}
		
		$res = new Result($st);
		return $res->hasError() ? $res->getCode() : true;
	
	}
	
	/**
	 *  Using this function for simple insert new row and get your id
	 *	In case of success this function will return index of the inserted row. 
	 *	In each error, this function will return false 
	 *  @param $insert string
	 *  @param $values array - ['ssi', 'value1', 'value2']
	 *  @param $aliasDB string - Use this if you need insert in other data base 
	 */		
	public static function lastInsert(string $insert, array $values, string $aliasDB = ""){
		
		if(count($values) < 2) throw new SQLiException(5); 
		
		if(($pdo = self::getDB($aliasDB)->get()) === null) return false;
		$status  = self::insert($insert, $values, $aliasDB, $pdo);
		
		if($status === true) return $pdo->lastInsertId();
		self::$lasterror = $status;

		return false;
		
	}

	/**
	 *  Using this function for inset many rows in same time
	 *	In case of success this function will return true. 
	 *	In each error, this function will return the error code 
	 *  @param $insert string - "table (value1, value2)"
	 *  @param $values array - ['ssi', ['value1A', 'value2A'],['value1B', 'value2B']]
	 *  @param $aliasDB string - Use this if you need insert in other data base 
	 */	
	public static function multiInsert(string $insert, array $values, string $aliasDB = ""){
		
		if(count($values) < 2) throw new SQLiException(5); 
		if(($pdo = self::getDB($aliasDB)->get()) === null) return false;
		
		$binds  = array_shift($values);
		$insert = "INSERT INTO ".trim($insert)." VALUES ";	
		$insert .= substr(str_repeat("(".substr(str_repeat("?,", count($values[0])), 0, -1).")," , count($values)), 0, -1);
		
		$st = $pdo->prepare($insert);
		if(!$st) throw new SQLiException(0, $pdo->errorInfo()[2]);
		
		$y = 1;
		foreach($values as $k => $rows){
			$i = 0;
			foreach($rows as $key => $val){
				$var = &$values[$k][$key];
				self::bind($st, $var, $y, $binds[$i]);
				$i++; $y++;
			}
		}
		
		$res = new Result($st);
		return $res->hasError() ? $res->getCode() : true;

	}

	/**
	 *  Using this function for inset/updates/creates/etc - NOT SELECT
	 *  This function will not return rows
	 *	In case of success this function will return true. 
	 *	In each error, this function will return the error code 
	 *  @param $exec string
	 *  @param $values array - ['ssi', 'value1', 'value2']
	 *  @param $aliasDB string - Use this if you need insert in other data base 
	 */
	public static function exec(string $exec, array $values = [], string $aliasDB = ""){
		
		if(($pdo = self::getDB($aliasDB)->get()) === null) return false;
		$st = $pdo->prepare($exec);
		
		if(!$st) throw new SQLiException(0, $pdo->errorInfo()[2]);
			
		if(count($values) > 1)
			self::setBinds($st, $values);
		
		$res = new Result($st);
		return $res->hasError() ? $res->getCode() : true;
		
	}
	
	/**
	 *  close all connections 
	 */
	public static function closeAll(){
		
		foreach(self::$databases as $db){
			$db->close();
		}
		
	}

	/**
	 *  close connection using alias
	 */
	public static function close($aliasDB){

		if(isset(self::$databases[$aliasDB])){
			self::$databases[$aliasDB] -> close();
		}

	}

	private static function hasKeys(array $keys){
		
		$ks = ['driver', 'host', 'dbname', 'user', 'pass'];
		$list = [];

		foreach ($ks as $value) if(!in_array($value, $keys)) $list[] = $value;
		if(count($list) > 0) throw new SQLiException(2, join(", ", $list));

	}

	private static function setBinds($st, array $values){

		$binds = array_shift($values);
		
		for($i = 0, $y = 1; $i < strlen($binds); $i++, $y++){
			
			$var = &$values[$i];
			self::bind($st, $var, $y, $binds[$i]);
			
		}
		
	}

	private static function bind($st, &$var, $y, $bind){

		switch ($bind) {
			case 'i':
				$bind = \PDO::PARAM_INT;
				break;
			case 'b':
				$bind = \PDO::PARAM_BOOL;
				break;
			case 'd':
				$var = strval($var);
				$bind = \PDO::PARAM_STR;
				break;
			case 's':
				$bind = \PDO::PARAM_STR;
				break;
			default:
				$bind = false;
				break;
		}

		!$bind ? $st->bindParam($y, $var) : $st->bindParam($y, $var, $bind);
	}

}