<?php

namespace Core\Support;


/**
 * This class is a wrapper around $_SESSION.
 */

class Session
{

  /**
   * Starting / resuming the session.
   */

  public static function start()
  {
    session_start();
  }


  /**
   * To be fixed~.
   * 
   * @return void
   */

  public static function init()
  { 
    if(self::get('current_page')) {
      self::set('previous_page', self::get('current_page'));
      self::set('current_page', $_SERVER['REQUEST_URI']);
    } else {
      self::set('current_page', $_SERVER['REQUEST_URI']);
      self::set('previous_page', '/');
    }
  }


  /**
   * Stores a single value.
   * 
   * @var mixed input
   * 
   * @return void
   */

  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }


  /**
   * Returns a value under provided key.
   * 
   * @var string key
   * 
   * @return mixed value
   */

  public static function get($key)
  {
    return isset($_SESSION[$key]) ?  $_SESSION[$key] : null;
  }


  /**
   * Creates an array under given key if it doesn't exist and
   * pushes provided value into the array.
   * 
   * @var string key
   * @var mixed value
   * 
   * @return void
   */

  public static function push($key, $value)
  {
    if(!isset($_SESSION[$key])) {
      $_SESSION[$key] = [$value];
    } else {
      $_SESSION[$key][] = $value;
    }
  }
}