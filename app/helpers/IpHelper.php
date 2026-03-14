<?php

namespace app\helpers;

class IpHelper
{
  public static function getClientIp(): string
  {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    // Normalizar localhost IPv6
    if ($ip === '::1') {
      $ip = '127.0.0.1';
    }

    // Revisar proxy headers
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

      $forwardedIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

      foreach ($forwardedIps as $forwardedIp) {

        $forwardedIp = trim($forwardedIp);

        if (filter_var($forwardedIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
          return $forwardedIp;
        }
      }
    }

    return $ip;
  }
}
