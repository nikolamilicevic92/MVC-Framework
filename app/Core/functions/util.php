<?php

 function dotsToSlashes($string)
 {
   return str_replace('.', '/', $string);
 }

 function csrf()
 {
   return $_SESSION['csrf'];
 }

 function csrfFormField()
 {
   return '<input type="hidden" name="_csrf" value="' . $_SESSION['csrf'] . '">';
 }

 function user()
 {
   return Core\Middleware\AuthMiddleware::getInstance();
 }

 function randomSecureToken($length = 32)
 {
   return bin2hex(random_bytes($length));
 }

 function passwordHash($password)
 {
   return password_hash($password, PASSWORD_DEFAULT);
 }

 function dump($object)
 {
   echo '<pre>';
   var_dump($object);
   echo '</pre>';
 }


