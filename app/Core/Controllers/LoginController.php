<?php

namespace Core\Controllers;

use Core\Controllers\BaseController;
use Core\Router\Request;
use Core\Models\User;
use Core\Support\Session;

/**
 * This class handles user reigstration.
 */

class LoginController extends BaseController
{

  /**
   * Remember me cookie duration in days.
   * 
   * @var int
   */

  private $rememberMeCookieDuration = 7;

  /**
   * @method GET
   * 
   * Rendering login page. URI: /login
   */
  
  public function index()
  {
    $this->render('auth.login');
  }


  /**
   * Logs out the user, unseting user variables in Session.
   * 
   * @return void
   */

  public function logout()
  {
    Session::set('user_status', 'guest');
    Session::set('user_id', -1);
    Session::set('user_email', '');
    Session::set('user_name', '');
    
    setcookie('remember_me', '', time() - 1);

    $this->redirect('/');
  }


  /**
   * @method POST
   * 
   * Testing for provided username / email combination. URI: LOGIN_URI
   */

  public function login(Request $request)
  {
    if($user = User::one(['email' => $request->body->email])) {

      if(password_verify($request->body->password, $user->password)) {

        self::loginUser($user);

        if($request->body->remember_me) {

          $this->rememberUser($user->token);
          
        }
        $this->redirect('/');
      }
    }
    $this->redirect(LOGIN_URI, [
      'errors' => ['Invalid email / password combination']
    ]);
  }


  /**
   * Sets user session variables, thus making the user logged in.
   * 
   * @var object user
   * 
   * @return void
   */

  public static function loginUser($user)
  {
    if($user->status === 2) {
      Session::set('user_status', 'admin');
    } else {
      Session::set('user_status', 'user');
    }
    Session::set('user_id', $user->id);
    Session::set('user_email', $user->email);
    Session::set('user_name', $user->name);
  }


  /**
   * Sets the remember me token.
   * 
   * @var string token
   * 
   * @return void
   */

  private function rememberUser($token)
  {
    $tokenDuration = time() + $this->rememberMeCookieDuration * 86400;

    $secure = strpos(SITE_URL, 'https://') === 0;

    setcookie(
      'remember_me', $token, $tokenDuration, "", "", $secure, true
    );
  }
}