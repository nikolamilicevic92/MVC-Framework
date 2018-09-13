<?php

/**
 * Preventing inline javascript and script tags to avoid XSS.
 */

// Route::middleware(function($request, $response) {
//   $response->CSP(
//     "script-src 'self' 'unsafe-eval'; child-src 'none'; object-src 'none'"
//   );
// });


/**
 * Here are the routes used by search engines.
 */

Route::get('/robots.txt', function( $request, $response ) {
  $response->sendFile('robots.txt');
});

Route::get('/sitemap.xml', function( $request, $response ) {
  $response->contentType('text/xml')->sendFile('sitemap.xml');
});


/**
 * Here are the routes that will be used for authentication.
 */

if(AUTHENTICATION == 'ON') {
  Route::get('/login', '$LoginController@index');
  Route::get('/logout', '$LoginController@logout');
  Route::post('/login', '$LoginController@login');

  Route::get('/register', '$RegisterController@index');
  Route::post('/register', '$RegisterController@register');
  Route::get('/register/:token', '$RegisterController@token');

  Route::get('/password-reset', '$PasswordResetController@index');
  Route::get('/password-reset/:token', '$PasswordResetController@token');
  Route::post('/password-reset', '$PasswordResetController@reset');
}

/**
 * Access to dev panel only when in dev mode.
 */

if(MODE === 'DEV') {
  Route::get('/dev-panel', '$DevPanelController@index');

  Route::post('/dev-panel/controller', '$DevPanelController@controller');

  Route::post('/dev-panel/model', '$DevPanelController@model');
}