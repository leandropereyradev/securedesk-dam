<?php

namespace app\helpers;

class UrlHelper
{
  public static function withQuery(string $route, array $params): string
  {
    return $route . '?' . http_build_query($params);
  }
}
