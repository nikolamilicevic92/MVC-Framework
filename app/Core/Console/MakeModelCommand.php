<?php

namespace Core\Console;

use Core\Models\Database;

class MakeModelCommand
{


  public static function execute($model, $createTable)
  {
    $content = self::generateModelContent($model);

    if(!file_put_contents(ROOT_DIR . "app/Models/$model.php", $content)) {
      return false;
    }
   
    if($createTable && !self::createTable($model)) {
      return false;
    }

    return true;    
  }


  private static function generateModelContent($model)
  {
    $content = "<?php \r\n\r\nnamespace Models;\r\n\r\n";
    $content .= "use Core\Models\BaseModel;\r\n\r\n";
    $content .= "class $model extends BaseModel\r\n{\r\n\t//\r\n}";

    return $content;
  }


  private static function createTable($model)
  {
    $conn = Database::getInstance()->connection;

    $patterns = ['/([a-z])([A-Z])/', '/([A-Z])([A-Z][a-z])/'];
    $table = strtolower(preg_replace($patterns, '$1_$2', $model));

    $conn->query(
      "create table $table (
        id int primary key auto_increment
      ) ENGINE = INNODB"
    );

    return $conn->error === '';
  }
}