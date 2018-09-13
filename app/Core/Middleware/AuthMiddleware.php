<?php

namespace Core\Middleware;

use Core\Support\Session;
use Core\Controllers\LoginController;
use Core\Models\User;

/**
 * Helper class containing user details.
 */

class AuthMiddleware
{

  private static $instance = null;


  private function __construct()
  {
    $this->status = Session::get('user_status');
    $this->id = Session::get('user_id');
    $this->name = Session::get('user_name');
    $this->email = Session::get('user_email');
  }

  public static function init()
  {
    if(!Session::get('user_status')) {
      if(isset($_COOKIE['remember_me'])) {
        if($user = User::one(['token' => $_COOKIE['remember_me']])) {
          LoginController::loginUser($user);
          User::where($user->id)->update('token')->with(randomSecureToken());
        } else {
          Session::set('user_status', 'guest');
        }
      } else {
        Session::set('user_status', 'guest');
      }
    }
    self::$instance = new self();
  }

  public static function getInstance()
  {
    if(!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function status(...$statuses)
  {
    foreach($statuses as $status) {
      if($status === $this->status) {
        return true;
      }
    }
    return false;
  }
}