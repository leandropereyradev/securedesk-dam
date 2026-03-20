<?php

use app\core\ViewContext;

if (!function_exists('view')) {
  function view(string $key, $default = null)
  {
    return ViewContext::get($key, $default);
  }
}
