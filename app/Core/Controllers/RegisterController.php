<?php

namespace Core\Controllers;

use Core\Controllers\BaseController;
use Core\Support\Validator;
use Core\Support\Mailer;
use Core\Router\Request;
use Core\Support\Session;
use Core\Models\User;

/**
 * This class handles user reigstration.
 */

class RegisterController extends BaseController
{

  private $data = [
    'errors'   => [], 
    'success'  => [],
    'name'     => '',
    'email'    => '',
    'password' => '',
    'confirm'  => ''
  ];

  /**
   * Rendering the register page.
   * 
   * @return void 
   */
  
  public function index()
  {
    $this->render('auth.register', $this->data);
  }


  /**
   * Storing new user data. User is not yet fully registered,
   * untill he visits the link that will be sent to him. 
   * 
   * @var Request request
   * 
   * @return void
   */

  public function register(Request $request)
  {
    $this->data = array_merge($this->data, $request->body->toArray());

    $this->validate($request->body);

    $this->checkForExistingEmail($request->body->email);

    $hash = passwordHash($request->body->password);

    $token = randomSecureToken();

    $this->storeRegisterData(
      $request->body->name, $request->body->email, $hash, $token
    );

    $this->mailAccountVerificationLink($request->body->email, $token);
   
    $this
      ->success('Follow the link sent at your email to complete the registration')
      ->toPage();
  }


  /**
   * Makes the previously stored user registered if the token
   * provided matches the one in session generated earlier.
   * 
   * @var string token
   * 
   * @return void
   */

  public function token($token)
  {
    if(Session::get('register_token') === $token) {
    
      User::store(Session::get('register_data'));

      Session::set('register_token', null);
      Session::set('register_data', null);
    
      $this
        ->success('Account has been verified, log in and have fun!')
        ->redirect(LOGIN_URI, $this->data);

    } else {

      $this->error('Token is invalid or has expired')->toPage();
      
    }
  }


  /**
   * Validates form data, pupulates errors data and redirects
   * back to the register page if any input field is invalid.
   * 
   * @var array body
   * 
   * @return void
   */

  private function validate($body)
  {
    if(!Validator::isName($body->name)) {
      $this->error('Name can contain only alphabet letters and spaces');
      $this->data['name'] = '';
    }
    if(!Validator::isEmail($body->email)) {
      $this->error('Invalid email format');
      $this->data['email'] = '';
    }
    if(!Validator::isPassword($body->password)) {
      $this->error('Password must be at least 8 characters long and contain at least one lowercase, uppercase and number character');
      $this->data['password'] = '';
      $this->data['confirm'] = '';
    }
    if(!Validator::isEqual($body->password, $body->confirm)) {
      $this->error('Passwords do not match');
      $this->data['confirm'] = '';
    }
    //Back to the register page, with the error messages
    if(count($this->data['errors'])) {
      $this->toPage();
    } else {
      $this->data['name'] = '';
      $this->data['email'] = '';
      $this->data['password'] = '';
      $this->data['confirm'] = '';
    }
  }


  /**
   * Aserts that email is not already in use.
   * 
   * @var string email
   * 
   * @return void
   */

  private function checkForExistingEmail($email)
  {
    if(User::one(['email' => $email])) {

      $this->data['email'] = '';

      $this
        ->error('Account with provided email is already registered')
        ->toPage();

    }
  }


  /**
   * Stores form data temporary inside the session untill the 
   * user visits the account verification link sent earlier.
   * 
   * @var string name
   * @var string email
   * @var string hash
   * @var string token
   * 
   * @return void
   */

  private function storeRegisterData($name, $email, $hash, $token)
  {
    Session::set('register_data', [
      'name' => $name, 'email' => $email, 
      'password' => $hash, 'token' => randomSecureToken()
    ]);
    Session::set('register_token', $token);
  }


  /**
   * Emails the link use has to visit to activate account.
   * 
   * @var string address
   * @var string token
   * 
   * @return void
   */

  private function mailAccountVerificationLink($address, $token)
  {
    Mailer::send(
      $address, 'Account verification', SITE_URL . REGISTER_URI . "/$token"
    );
  }

  /**
   * Populating the errors array.
   * 
   * @var string message
   * 
   * @return object this
   */

  private function error($message)
  {
    $this->data['errors'][] = $message;

    return $this;
  }


  /**
   * Populating the success array.
   * 
   * @var string message
   * 
   * @return object this
   */

  private function success($message)
  {
    $this->data['success'][] = $message;

    return $this;
  }


  /**
   * Navigating back to register page with messages in data
   * array being flushed to the page as well.
   */

  private function toPage()
  {
    $this->redirect(REGISTER_URI, $this->data);
  }

}