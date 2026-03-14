<?php

namespace app\routes;

use app\controllers\SessionController;
use app\controllers\TicketCommentsController;
use app\helpers\RedirectHelper;

class CommentRoutes
{
  public static function commentAddPost()
  {
    SessionController::requireLogin();

    TicketCommentsController::addComment($_POST);

    RedirectHelper::to('ticket?id=' . (int)$_POST['ticket_id']);
    exit;
  }
}
