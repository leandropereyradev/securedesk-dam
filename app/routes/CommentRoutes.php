<?php

namespace app\routes;

use app\controllers\SessionController;
use app\controllers\TicketCommentsController;

class CommentRoutes
{
  public static function commentAddPost()
  {
    SessionController::requireLogin();

    TicketCommentsController::addComment($_POST);

    header('Location: ' . APP_URL . 'ticket?id=' . (int)$_POST['ticket_id']);
    exit;
  }
}
