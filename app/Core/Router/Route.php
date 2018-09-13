<?php

use Core\Router\Request;
use Core\Controllers\BaseController;
use Core\Middleware\CSRFMiddleware;
use Core\Support\Session;

/**
 * This class is used to test whether incoming uri matches
 * the defined and performs a corresponding action if so.
 */

class Route
{
  private static $request;
  private static $response;
  public static $routeWasMatched = false;
  private static $accessForbidden = false;
  private static $action;
  private static $controller;
  private static $method;
  private static $statuses;
  private static $uriPrefix = '';
  private static $publicURIs = [];
  private static $resourceURIs = [
    'index'   => ['GET'   , '/:resource'         ],
    'create'  => ['GET'   , '/:resource/create'  ],
    'show'    => ['GET'   , '/:resource/:id'     ],
    'edit'    => ['GET'   , '/:resource/:id/edit'],
    'store'   => ['POST'  , '/:resource'         ],
    'update'  => ['PUT'   , '/:resource/:id'     ],
    'destroy' => ['DELETE', '/:resource/:id'     ]
  ];


  /**
   * Initializes class fields.
   * 
   * @return void
   */

  public static function init()
  {
    self::$request = Request::getInstance();
    self::$response = new BaseController();
  }


  /**
   * Adds URIs for which csrf protection is disabled.
   * 
   * @var array URIs
   * 
   * @return void
   */

  public static function publicURIs($URIs)
  {
    self::$publicURIs = $URIs;
  }


  /**
   * Sets a prefix to be used on defined uri when testing
   * for match with incoming uri.
   * 
   * @var string prefix
   * 
   * @return void
   */

  public static function prefix($prefix)
  {
    self::$uriPrefix = $prefix;
  }


  /**
   * Middleware that executes the provided closure.
   * 
   * @var object closure
   * 
   * @return void
   */

  public static function middleware($closure)
  {
    if(self::$routeWasMatched) self::performAction();

    $closure(self::$request, self::$response);
  }


  /**
   * Tests for all resource URIs of provided resource and maps
   * them to corresponding method of the provided controller.
   * 
   * @var string resource
   * @var string controller
   * 
   * @return void
   */

  public static function resource($resource, $controller)
  {
    if(self::$routeWasMatched) self::performAction();

    foreach(self::$resourceURIs as $method => $args) {
      if(self::$routeWasMatched) break;
      $uri = str_replace(':resource', $resource, $args[1]);
      $verb = $args[0];
      $action = "$controller@$method";
      self::resolve($uri, $action, $verb);
    } 

    return new self();
  }


  /**
   * Tests if incoming URI matches any of provided pages
   * and invokes the corresponding controller method.
   * 
   * @var array pages
   * @var string controller
   * 
   * @return void
   */

  public static function map($pages, $controller)
  {
    if(self::$routeWasMatched) self::performAction();

    foreach($pages as $page) {
      if(self::$routeWasMatched) break;
      $action = $page == '/' ? 'index' : substr($page, 1);
      self::resolve("$page", "$controller@$action", 'GET');
    }

    return new self();
  }


  /**
   * Methods for handing all HTTP verbs are defined bellow.
   * 
   * @var string uri
   * @var string action
   * 
   * @return void
   */

  public static function get($uri, $action)
  {
    self::resolve($uri, $action, 'GET');
    return new self();
  }

  public static function post($uri, $action)
  {
    self::resolve($uri, $action, 'POST');
    return new self();
  }

  public static function put($uri, $action)
  {
    self::resolve($uri, $action, 'PUT');
    return new self();
  }

  public static function patch($uri, $action)
  {
    self::resolve($uri, $action, 'PATCH');
    return new self();
  }

  public static function delete($uri, $action)
  {
    self::resolve($uri, $action, 'DELETE');
    return new self();
  }


  /**
   * Tests if route was hit and prepares the action if it was.
   * Tests for CSRF token as well. Action will be executed on
   * next route method, as access rights have to be tested.
   * 
   * @var string uri
   * @var string action
   * @var string verb
   * 
   * @return void
   */

  private static function resolve($uri, $action, $verb)
  {
    if(self::$routeWasMatched) self::performAction();

    if(self::verbMatched($verb) && self::uriMatched($uri)) {

      self::validateRequest($verb, $uri);

      self::$routeWasMatched = true;
      
      self::setAction($action);
    }
  }


  /**
   * If action is a closure, executes it, otherwise creates
   * a controller and calls its method based of the action
   * string, but only if user has an access to the route.
   * 
   * @return void 
   */

  private static function performAction()
  {
    //If action is a function closure
    if(gettype(self::$action) != 'string') {
      $closure = self::$action;
      $closure(self::$request, self::$response);
    } else {
      $method = self::$method;
      //If it is core controller (prefixed with $)
      if(strpos(self::$controller, '$') === 0) {
        $controller = 'Core\\Controllers\\'. substr(self::$controller, 1);
        $instance = self::getControllerInstance($controller, true);
      } else {
        $controller = self::$controller;
        $instance = self::getControllerInstance($controller);
      }
      $arguments = self::getMethodArguments($controller, $method);
      $instance->$method(...$arguments);
    }
    die;
  }


  /**
   * Tests if request is valid by validating csrf token if required.
   * 
   * @var string verb
   * @var string uri
   * 
   * @return void
   */

  private static function validateRequest($verb, $uri)
  {
    if($verb != 'GET' && !in_array($uri, self::$publicURIs)) {

      CSRFMiddleware::validate();
      
    }
  }


  /**
   * Restricts a route for users without provided statuses.
   * 
   * @var array statuses
   * 
   * @return object this
   */

  public function allowTo(...$statuses)
  {
    if(self::$routeWasMatched) {
      if(!in_array(self::$request->userStatus, $statuses)) {
        self::$response->back();
      }
    }
    return $this;
  }


  /**
   * Restricts a route for users whose status is among the
   * provided statuses.
   * 
   * @var array statuses
   * 
   * @return object this
   */

  public function denyTo(...$statuses)
  {
    if(self::$routeWasMatched) {
      if(in_array(self::$request->userStatus, $statuses)) {
        self::$response->back();
      }
    }
    return $this;
  }


  /**
   * Initializes statuses that will be checked on next allow/deny
   * 
   * @var array statuses
   * 
   * @return object this
   */

  public function when(...$statuses)
  {
    if(self::$routeWasMatched) {
      self::$statuses = $statuses;
    }
    return $this;
  }


  /**
   * Forbids the access to all methods except the provided ones
   * to users whose status is among those set in last when().
   * 
   * @var array methods
   * 
   * @return object this
   */

  public function allow(...$methods)
  {
    if(self::$routeWasMatched) {
      if(in_array(self::$request->userStatus, self::$statuses) &&
       !in_array(self::$method, $methods)) {
        self::$response->back();
      }
    }
    return $this;
  }


  /**
   * Denies access to the provided controller methods for
   * users whose status is among those set in last when().
   * 
   * @var array methods
   * 
   * @return object this
   */

  public function deny(...$mehtods)
  {
    if(self::$routeWasMatched) {
      if(in_array(self::$request->userStatus, self::$statuses) &&
         in_array(self::$method, $methods)) {

        self::$response->back();
      }
    }
    
    return $this;
  }


  /**
   * Tests if provided uri matches the incoming uri.
   * 
   * @var string uri
   * 
   * @return bool
   */

  private static function uriMatched($uri)
  {
    return self::$request->uriMatched(self::$uriPrefix . $uri);
  }


  /**
   * Tests if request method matches the provided method.
   * 
   * @var string method
   * 
   * @return bool
   */

   private static function verbMatched($verb)
   {
     return self::$request->method == $verb;
   }


  /**
   * Sets the action that will be performed at next resolve call.
   * 
   * @var string action
   * 
   * @return void
   */

  private static function setAction($action)
  {
    self::$action = $action;
    if(gettype($action) == 'string') {
      $action = explode('@', $action);
      self::$controller = $action[0];
      self::$method = $action[1];
    }
  }


  /**
   * Uses reflection to inject the request if required inside
   * the controller method. Injects URI parameters as well.
   * 
   * @var string controller
   * @var string method
   * 
   * @return array arguments
   */

  private static function getMethodArguments($controller, $method)
  {
    $arguments = [];
    $params = (new ReflectionMethod($controller, $method))->getParameters();
    foreach ($params as $param) {
      if($type = $param->getType()) {
        if($type->getName() === 'Core\\Router\\Request') {
          $arguments[] = self::$request;
        }
      } else {
        $arguments[] = self::$request->params[$param->getName()];
      }
    }
    return $arguments;
  }


  /**
   * Returns an instance of controller to handle the request.
   * 
   * @var string controller
   * 
   * @return object instance
   */

  private static function getControllerInstance($controller, $isCore = false)
  {
    if($isCore) {
      $instance = new $controller();
    } else {
      require ROOT_DIR .'controllers/'. $controller .'.php';
      $instance = new $controller();
    }
    return $instance;
  }
}

Route::init();