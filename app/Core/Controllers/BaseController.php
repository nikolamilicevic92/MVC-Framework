<?php

namespace Core\Controllers;

use Core\View\TemplateEngine;
use Core\Support\Session;

/**
 * This is the base controller class with some usefull methods.
 */

class BaseController
{
  /**
   * Just a dummy placeholder data.
   * 
   * @var array
   */

  protected $baseData = [
    'title' => 'MVC Framework',
    'keywords' => 'MVC, Framework, PHP',
    'description' => 'Simple, lightweight, easy to use MVC Framework',
    'scripts' => [],
    'stylesheets' => []
  ];


  /**
   * An array of header status messages.
   * 
   * @var array
   */

  private $statuses = [
    100 => 'HTTP/1.1 100 Continue',
    101 => 'HTTP/1.1 101 Switching Protocols',
    200 => 'HTTP/1.1 200 OK',
    201 => 'HTTP/1.1 201 Created',
    202 => 'HTTP/1.1 202 Accepted',
    203 => 'HTTP/1.1 203 Non-Authoritative Information',
    204 => 'HTTP/1.1 204 No Content',
    205 => 'HTTP/1.1 205 Reset Content',
    206 => 'HTTP/1.1 206 Partial Content',
    300 => 'HTTP/1.1 300 Multiple Choices',
    301 => 'HTTP/1.1 301 Moved Permanently',
    302 => 'HTTP/1.1 302 Found',
    303 => 'HTTP/1.1 303 See Other',
    304 => 'HTTP/1.1 304 Not Modified',
    305 => 'HTTP/1.1 305 Use Proxy',
    307 => 'HTTP/1.1 307 Temporary Redirect',
    400 => 'HTTP/1.1 400 Bad Request',
    401 => 'HTTP/1.1 401 Unauthorized',
    402 => 'HTTP/1.1 402 Payment Required',
    403 => 'HTTP/1.1 403 Forbidden',
    404 => 'HTTP/1.1 404 Not Found',
    405 => 'HTTP/1.1 405 Method Not Allowed',
    406 => 'HTTP/1.1 406 Not Acceptable',
    407 => 'HTTP/1.1 407 Proxy Authentication Required',
    408 => 'HTTP/1.1 408 Request Time-out',
    409 => 'HTTP/1.1 409 Conflict',
    410 => 'HTTP/1.1 410 Gone',
    411 => 'HTTP/1.1 411 Length Required',
    412 => 'HTTP/1.1 412 Precondition Failed',
    413 => 'HTTP/1.1 413 Request Entity Too Large',
    414 => 'HTTP/1.1 414 Request-URI Too Large',
    415 => 'HTTP/1.1 415 Unsupported Media Type',
    416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
    417 => 'HTTP/1.1 417 Expectation Failed',
    500 => 'HTTP/1.1 500 Internal Server Error',
    501 => 'HTTP/1.1 501 Not Implemented',
    502 => 'HTTP/1.1 502 Bad Gateway',
    503 => 'HTTP/1.1 503 Service Unavailable',
    504 => 'HTTP/1.1 504 Gateway Time-out',
    505 => 'HTTP/1.1 505 HTTP Version Not Supported',
  ];


  /**
   * Renders the specified view, making values of data array
   * available inside it, as well as data that was put inside
   * the session in previous request under redirect_data.
   * 
   * @var string view
   * @var array  data
   * 
   * @return void
   */

  public function render($view, $data = [])
  {
    if($redirectData = Session::get('redirect_data')) {

      $data = array_merge($data, $redirectData);

      Session::set('redirect_data', null);
    }

    TemplateEngine::render($view, array_merge($this->baseData, $data));
  }


  /**
   * Displays json formated response.
   * 
   * @var array input
   * 
   * @return void
   */

   public function json($input)
   {
     $this->contentType('application/json');

     echo json_encode($input);
   }


   /**
    * Displays raw text.
    * 
    * @var string input
    *
    * @return void
    */

    public function send($input)
    {
      $this->contentType('text/plain');
      echo $input;
    }


    /**
     * Displays the content of a file.
     * 
     * @var string file
     * 
     * @return void
     */

    public function sendFile($file)
    {
      echo  file_get_contents(ROOT_DIR . $file);
    }


    /**
     * Sets the location header and optional redirect data.
     * 
     * @var string location
     * @var array data
     * 
     * @return void
     */

    public function redirect($location, $data = [])
    {
      Session::set('redirect_data', $data);

      header("Location: $location");

      die;
    }


    /**
     * Sets the Content-Security-Policy header.
     * 
     * @var string policy
     * 
     * @return void
     */

    public function CSP($policy)
    {
      header("Content-Security-Policy: $policy");
      
      return $this;
    }


    /**
     * Redirects the user to the previous URI with optional data.
     * 
     * @var array data
     * 
     * @return void
     */

    public function back($data = [])
    {
      $this->redirect(Session::get('previous_page'), $data);
    }


    /**
     * Terminates the script with optional provided message.
     * 
     * @var string message
     * 
     * @return void
     */

    public function end($message = '')
    {
      die($message);
    }


    /**
     * Sets the header status.
     * 
     * @var int status
     * 
     * @return object this
     */

    public function status($status)
    {
      header($this->statuses[$status]);
      return $this;
    }


    /**
     * Sets the Content-type header.
     * 
     * @var string contentType
     * 
     * @return object this
     */

    public function contentType($contentType)
    {
      header("Content-type: $contentType");
      return $this;
    }


    /**
     * Prevents the client from caching by setting appropriate headers.
     * 
     * @return object this
     */

    public function noCache()
    {
      header("Cache-Control: no-cache, must-revalidate");
      header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
      return $this;
    }


    /**
     * Forces a download of given file.
     * 
     * @var string path
     * 
     * @return void
     */

    public function download($path, $name = false)
    {
      if(!$name) $name = pathinfo($path, PATHINFO_BASENAME);
      $path = ROOT_DIR . $path;
      $this->contentType(mime_content_type($path));
      header("Content-Disposition: attachment; filename='$name'");
      readfile($path);
    }
}