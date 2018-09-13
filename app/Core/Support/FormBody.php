<?php

namespace Core\Support;

/**
 * Just an object oriented way of targeting form body.
 */

class FormBody
{
  private $data = [];


  public function __construct()
  {
    $this->data = $_REQUEST;

    $this->pop('_csrf')->pop('PHPSESSID')->pop('_method');
  }


  public function __get($key)
  {
    return isset($this->data[$key]) ? trim($this->data[$key]) : null;
  }


  public function pop($key)
  {
    unset($this->data[$key]);

    return $this;
  }


  public function push($key, $value)
  {
    $this->data[$key] = $value;

    return $this;
  }


  public function toArray()
  {
    return $this->data;
  }
}