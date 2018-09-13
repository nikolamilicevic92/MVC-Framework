<?php

namespace Core\Support;

/**
 * This class is used as a wrapper for uploaded files.
 */

class File
{
  private $filename;
  private $size;
  private $type;
  private $tmpName;
  private $error;
  public $errors = [];
  private $uniqueFilename;
  private $destination = 'uploads';
  private $valid = true;


  /**
   * Initializing class fields.
   */

  public function __construct($file)
  {
    $this->filename = $file['name'];
    $this->size = $file['size'] / 1000;
    $this->type = $file['type'];
    $this->tmpName = $file['tmp_name'];
    $this->error = $file['error'];
    $this->uniqueFilename = $this->getUniqueFilename($file['name']);
  }


  /**
   * Validates file size and type against the provided ones.
   * 
   * @var int size (KB)
   * @var string type
   * 
   * @return object this
   */

  public function validate($arg1, $arg2)
  {
    if(gettype($arg1) === 'string') {
      $type = $arg1;
      $size = $arg2;
    } else {
      $type = $arg2;
      $size = $arg1;
    }
    if($this->error) {
      $this->pushError("Something went wrong when uploading $this->filename.");
    }
    if($this->size > $size) {
      $this->pushError("File $this->filename must be under $size Kb.");
    }
    if(!(strpos($this->type, $type) === 0)) {
      $this->pushError("File $this->filename must be of type $type.");
    }
    return $this;
  }


  /**
   * Pushes the provided error into errors field, setting
   * file validity to false.
   * 
   * @var string error
   * 
   * @return void
   */

  private function pushError($error)
  {
    $this->valid = false;
    $this->errors[] = $error;
  }


  /**
   * Foreach file in $_FILES creates an instance of File class.
   * Returns an array in form of ['name' => File, ...].
   * 
   * @return array 
   */

  public static function all()
  {
    $files = [];
    foreach($_FILES as $name => $file) {
      //If it is not multiple upload under same name
      if(gettype($file['name']) === 'string' && $file['size']) {
        $files[$name] = new self($file);
      } else if($file['size'][0]) {
        $files[$name] = [];
        for($i = 0; $i < count($file['name']); $i++) {
          $files[$name][] = new self([
            'name' => $file['name'][$i],
            'type' => $file['type'][$i],
            'tmp_name' => $file['tmp_name'][$i],
            'size' => $file['size'][$i],
            'error' => $file['error'][$i]
          ]);
        }
      }
    }
    return $files;
  }


  /**
   * Moves uploaded file in defined destination.
   * 
   * @return string path
   */

  public function upload()
  {
    if(!$this->valid) return false;

    $path = $this->destination . $this->uniqueFilename;

    move_uploaded_file($this->tmpName, ROOT_DIR . $path);

    return $path;
  }


  /**
   * Combines destination() method and upload() method.
   * 
   * @var string path
   * 
   * @return string pathToFile
   */

  public function uploadTo($path)
  {
    $this->destination($path);
    return $this->upload();
  }


  /**
   * Sets a destination directory for file to be moved to,
   * creating it if it does not exist.
   * 
   * @var string path
   * 
   * @return object this
   */

  public function destination($path)
  {
    $path = str_replace('\\', '/', $path);
    if(strpos($path, '/') === 0) $path = substr($path, 1);
    $lastChar = substr($path, -1);
    if($lastChar !== '/') $path .= '/';
    if(!file_exists(ROOT_DIR . $path)) $this->makePath($path);
    $this->destination = $path;
    return $this;
  }


  /**
   * Instructs the class to not use random unique names.
   * 
   * @return object this
   */

  public function overwrite()
  {
    $this->uniqueFilename = $this->filename;
    return $this;
  }


  /**
   * Computes a unique and random filename.
   * 
   * @var string filename
   * 
   * @return string uniqueFilename
   */

  private function getUniqueFilename($filename)
  {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    return md5(uniqid('', true) . $filename) . ".$ext";
  }


  /**
   * Creates all nececary folders for path to exist.
   * 
   * @var string path
   * 
   * @return void
   */

  public function makePath($path)
  {
    $directories = array_diff( explode('/', $path), [''] );
    $path = ROOT_DIR;
    foreach($directories as $directory) {
      $path .= $directory . '/';
      if(!file_exists($path)) mkdir($path);
    }
  }
}