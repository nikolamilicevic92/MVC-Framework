<?php

namespace Core\Console;

class MakeControllerCommand
{   

  public static function execute($controllerName, $isResource)
  {
    if($isResource) {
      $sample = file_get_contents(
        ROOT_DIR . 'app/Core/Console/ResourceController.php'
      );
      $content = str_replace(
        'ResourceController', $controllerName, $sample
      );
    } else {
      $content = "<?php \r\n\r\nuse Core\Controllers\BaseController;\r\n";
      $content .= "use Core\Router\Request;\r\n\r\n";
      $content .= "class $controllerName extends BaseController\r\n{\r\n\t//\r\n}";
    }

    return file_put_contents(
      ROOT_DIR . "app/Controllers/$controllerName.php", $content
    );
  }

}