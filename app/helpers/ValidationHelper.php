<?php

namespace app\helpers;

class ValidationHelper
{
  public static function required(?string $value): bool
  {
    return isset($value) && trim($value) !== '';
  }

  public static function maxLength(?string $value, int $length): bool
  {
    return mb_strlen($value ?? '') <= $length;
  }

  public static function inArray(?string $value, array $allowed): bool
  {
    return in_array($value, $allowed, true);
  }
}
