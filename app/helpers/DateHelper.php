<?php

namespace app\helpers;

use DateTime;
use DateTimeZone;

class DateHelper
{
  public static function utcToMadrid(?string $utcTime): ?string
  {
    if (!$utcTime) {
      return null;
    }

    $date = new DateTime($utcTime, new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone('Europe/Madrid'));

    return $date->format('d-m-Y H:i:s');
  }
}
