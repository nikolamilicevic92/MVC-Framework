<?php

namespace Core\Models;

/**
 * This class provides a singleton around mysql connection.
 */

class Database {

  private static $instance = null;
  public $connection;


  private function __construct()
  {
    $this->connection = new \mysqli(...self::credentials());

    if(!$this->connection) {

      die('Failed connecting to database');

    }

    $this->connection->set_charset('utf8');
  }


  private static function credentials()
  {
    return ['127.0.0.1', 'root', '', 'test'];
  }
  

  public static function getInstance() 
  {
		if(!self::$instance) { 

      self::$instance = new self();
      
    }
		return self::$instance;
	}
  
  
	private function __clone() { }
}