<?php

namespace Core\Support;

/**
 * This class is used to test inputs agains provided conditions.
 */

class Validator
{

  public static function isName($input)
  {
    return preg_match("/^[a-zA-Z]+\s?[a-zA-Z]*$/", $input);
  }

  public static function isAlpha($input)
  {
    return ctype_alpha($input);
  }

  public static function isAlphanumeric($input)
  {
    return ctype_alnum($input);
  }

  public static function isNumeric($input)
  {
    return ctype_digit($input);
  }

  public static function isPassword($input, $strength = 1)
  {
    $lowercaseLettersPattern = '/[a-z]{'. $strength .',}/';
    $uppercaseLettersPattern = '/[A-Z]{'. $strength .',}/';
    $numbersPattern = '/[0-9]{'. $strength .',}/';
    $specialCharactersPattern = '/[!@#$&*]{'. ($strength - 1) .',}/';

    return (
      strlen($input) >= 8                          &&
      preg_match($lowercaseLettersPattern, $input) &&
      preg_match($uppercaseLettersPattern, $input) &&
      preg_match($numbersPattern, $input)          &&
      preg_match($specialCharactersPattern, $input)
    );
  }

  public static function isEqual($input, $expected)
  {
    return $input === $expected;
  }

  public static function isEmail($input)
  {
    return filter_var($input, FILTER_VALIDATE_EMAIL);
  }

  public static function pattern($input, $pattern)
  {
    return preg_match($pattern, $input);
  }

  public static function maxSize($input, $size)
  {
    return $input <= $size;
  }

  public static function minSize($input, $size)
  {
    return $input >= $size;
  }

  public static function maxLength($input, $length)
  {
    return strlen($input) <= $size;
  }

  public static function minLength($input, $length)
  {
    return strlen($input) >= $size;
  }

}