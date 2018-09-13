<?php

namespace Core\Support;

/**
 * This class is used to handle sql errors based on MODE constant.
 */

class Logger
{
  private static $logPath = ROOT_DIR . 'logs/db_logs.txt';


  /**
   * Writes the message in log file or throws an exception if
   * 
   */

  public static function logDBError($message)
  {
    $fh = fopen(self::$logPath, 'a');
    fputs($fh, date('Y-m-d H:i:s') . "\r\n");
    fputs($fh, "$message\r\n\r\n");
    fclose($fh);
  }


  /**
   * Empties the content of logs/db_logs.txt file.
   * 
   * @return void
   */

  public static function emptyDBErrors()
  {
    file_put_contents(self::$logPath, '');
  }
}