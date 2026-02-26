<?php

namespace app\models;

class ViewsModel
{
  protected function getViewsModel(string $view): ?string
  {
    $whitelist = [
      'home',
      'login',
      "dashboard",
      "tickets",
      "ticket-edit",
      "ticket-create",
      "users"
    ];

    if (!in_array($view, $whitelist, true)) {
      return null;
    }

    $path = ROOT . "app/views/content/{$view}-view.php";

    return is_file($path) ? $path : null;
  }
}
