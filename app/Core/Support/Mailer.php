<?php

namespace Core\Support;

/**
 * This class is used for sending emails.
 */

class Mailer
{
  private static $fake = true;

  public static function send($to, $subject, $body)
  {
    if(self::$fake) {
      $content = "To:\r\n\r\n$to\r\n\r\n";
      $content .= "Subject:\r\n\r\n$subject\r\n\r\n";
      $content .= "Body:\r\n\r\n$body\r\n\r\n";
      
      file_put_contents(ROOT_DIR . 'logs/inbox.txt', $content);
    }
  }
}