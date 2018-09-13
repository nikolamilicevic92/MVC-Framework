<?php



 /**
  * 'map' method will map GET URIs to equaly named methods of
  * the provided controller. '/' URI will call index method. 
  */
// Route::map([], 'PagesController');


/**
 * 'resource' method will map resource URIs to corresponding
 * methods of provided controller. Makes life a bit easier!
 */

// Route::resource('posts', 'PostsController')
  // ->when('guest')->allow('index', 'show');
  // ->when('guest')->deny('update');

Route::get('/', function($request, $response) {
  $response->render('welcome');
});