<?php

namespace app\core;

class ViewContext
{
  private static array $data = [];

  public static function set(string $key, $value): void
  {
    self::$data[$key] = $value;
  }

  public static function get(string $key, $default = null)
  {
    return self::$data[$key] ?? $default;
  }
}
