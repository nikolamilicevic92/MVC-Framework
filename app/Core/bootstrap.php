<?php

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))) . '/');

spl_autoload_register(function($className) {
  require_once ROOT_DIR .'app/'. lcfirst($className) . '.php';
});


require ROOT_DIR . 'app/Core/functions/util.php';

require ROOT_DIR . 'app/Core/constants.php';

if(MODE === 'PRODUCTION') error_reporting(0);



Core\Support\Session::start();

Core\Middleware\CSRFMiddleware::init();

Core\Middleware\AuthMiddleware::init();




require ROOT_DIR . 'app/Core/router/Route.php';

require ROOT_DIR . 'routes/public-URIs.php';

require ROOT_DIR . 'app/Core/router/base-routes.php';

require ROOT_DIR . 'routes/web.php';

require ROOT_DIR . 'routes/api.php';

Route::middleware(function($request, $response) {
  $response->back();
});

