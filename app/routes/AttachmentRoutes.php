<?php

namespace app\routes;

use app\controllers\AttachmentsController;
use app\controllers\AuditLogsController;
use app\controllers\SessionController;
use app\helpers\RedirectHelper;

class AttachmentRoutes
{
  public static function uploadPost()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_POST['ticket_id'];

    $attachmentId = AttachmentsController::upload($ticketId);

    AuditLogsController::logAttachment(
      $_SESSION['user_id'],
      'upload',
      $attachmentId,
      "En Ticket #$ticketId"
    );

    RedirectHelper::to('ticket?id=' . $ticketId);
    exit;
  }

  public static function attachmentDownloadGet()
  {
    SessionController::requireLogin();

    $attachmentId = (int)$_GET['id'];
    $ticketId = (int)$_GET['ticket_id'];

    AuditLogsController::logAttachment(
      $_SESSION['user_id'],
      'download',
      $attachmentId,
      "De Ticket #$ticketId"
    );

    AttachmentsController::download($attachmentId, $ticketId);
  }
}
