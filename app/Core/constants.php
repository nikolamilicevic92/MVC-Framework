<?php

/**
 * Defining constants based of app.config file.
 */

$file = file(ROOT_DIR . 'app.config');

foreach($file as $line) {
  $line = trim($line);
  if($line != '') {
    $line = explode('=', $line);
    define(trim($line[0]), trim($line[1]));
  }
}
