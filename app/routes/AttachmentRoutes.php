<?php

namespace app\routes;

use app\controllers\AttachmentsController;
use app\controllers\SessionController;

class AttachmentRoutes
{
  public static function uploadPost()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_POST['ticket_id'];

    AttachmentsController::upload($ticketId);

    header('Location: ' . APP_URL . 'ticket?id=' . $ticketId);
    exit;
  }

  public static function attachmentDownloadGet()
  {
    SessionController::requireLogin();

    AttachmentsController::download((int)$_GET['id']);
  }
}
