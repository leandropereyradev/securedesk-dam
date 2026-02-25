<?php

namespace app\controllers;

use app\models\ViewsModel;

class ViewsController extends ViewsModel
{
  public function getViewsController(string $view): string
  {
    return $this->getViewsModel($view) ?? '404';
  }
}
