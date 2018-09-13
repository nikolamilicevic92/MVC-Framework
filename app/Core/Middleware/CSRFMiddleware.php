<?php

namespace Core\Middleware;

use Core\Support\Session;
use Core\Router\Request;

/**
 * This class is used for generating and validating csrf token.
 */

class CSRFMiddleware
{

  /**
   * Enable if you want to regenerate csrf on each get request.
   * 
   * @var bool
   */

  private static $csrfPerRequest = false;


  /**
   * Generates a fresh token if needed.
   * 
   * @return void
   */

  public static function init()
  {
    $request = Request::getInstance();

    /**
     * If it is a GET request and token was not set earlier, or if it is
     * required that fresh token is set on each request we set it now.
     */

    if($request->method === 'GET') {

      if(!Session::get('csrf') || self::$csrfPerRequest) {

        self::generateToken();

      }
    }

  }


  /**
   * Generates a random csrf token.
   * 
   * @return void
   */

  private static function generateToken()
  {
    Session::set('csrf', bin2hex(random_bytes(32)));
  }


  /**
   * Tests for a match between provided token and the one in session.
   * Regenerates a new token if validation was successful.
   * 
   * @var string token
   * 
   * @return bool
   */

  public static function validate()
  {
    $request = Request::getInstance();
    $csrf = Session::get('csrf');

    if(!$csrf) self::rejectRequest();

    $isValid = hash_equals($request->csrf, $csrf);

    self::generateToken();

    if(!$isValid) {

      self::rejectRequest();

    }
  }


  /**
   * Terminates the request if csrf validation failed.
   * 
   * @return void
   */

  private static function rejectRequest()
  {
    header('HTTP/1.1 403 Forbidden'); 

    die(MODE == 'DEV' ? 'Request is missing the required CSRF token' : '');
  }

}
