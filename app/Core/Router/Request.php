<?php

namespace Core\Router;

use Core\Support\File;
use Core\Support\Session;
use Core\Support\FormBody;

/**
 * This class is responsible for parsing incoming request.
 */

class Request
{

  /**
   * The Singleton instance.
   * 
   * @var mixed
   */

  private static $instance = null;


  /**
   * An associative array of URI parameters.
   * 
   * @var array
   */

  public $params = [];


  /**
   * An associatie array of uploaded files.
   * 
   * @var array
   */

  public $files = [];


  /**
   * The request method.
   * 
   * @var string
   */

  public $method;


  /**
   * The incoming URI.
   * 
   * @var string
   */

  public $uri;


  /**
   * The incoming URI as array, split by '/'.
   * 
   * @var array
   */

  private $uriArray;


  /**
   * The length of URI in array form.
   * 
   * @var int
   */

  private $uriArrayLength;


  /**
   * An object containing request body.
   * 
   * @var array
   */

  public $body;


  /**
   * Initializing class fields.
   */

  private function __construct()
  {
    $this->method = $this->parseMethod();
    $this->uri = urldecode($_SERVER['REQUEST_URI']);
    $this->uriArray = explode('/', $this->uri);
    $this->uriArrayLength = count($this->uriArray);
    $this->csrf = isset($_REQUEST['_csrf']) ? $_REQUEST['_csrf'] : '';
    $this->body = new FormBody();
    $this->userID = Session::get('user_id');
    $this->userStatus = Session::get('user_status');
    $this->files = File::all();
  }


  public static function getInstance()
  {
    if(!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }


  /**
   * Tests if given uri matches the incoming uri.
   * 
   * @var string uri
   * 
   * @return bool
   */

  public function uriMatched($uri)
  {
    if($uri == $this->uri || $uri == '**') return true;

    $uri = explode('/', $uri);

    if(count($uri) != $this->uriArrayLength) return false;

    for($i = 1; $i < $this->uriArrayLength; $i++) {
      if($uri[$i] == '*') continue;
      if($this->uriArray[$i] != $uri[$i]) {
        if(strpos($uri[$i], ':') === 0) {
          //Store parameter and continue
          $this->params[substr($uri[$i], 1)] = $this->uriArray[$i];
        } else {
          return false;
        }
      }
    }
    return true;
  }


  /**
   * Returns a type of request method.
   * 
   * @return string
   */

  private function parseMethod()
  {
    if(isset($_REQUEST['_method'])) {
      return strtoupper($_REQUEST['_method']);
    } else {
      return $_SERVER['REQUEST_METHOD'];
    }
  }


  /**
   * Returns an object of type File or false if file doesn't exist.
   * 
   * @var string name
   * 
   * @return mixed
   */

  public function file($name)
  {
    if(isset($this->files[$name])) return $this->files[$name];

    return false;
  }

}