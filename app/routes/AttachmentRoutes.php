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

    $attachment = AttachmentsController::upload($ticketId);

    if ($attachment['error']) {
      RedirectHelper::error(
        'ticket?id=' . $ticketId,
        $attachment['error']
      );
    }

    AuditLogsController::logAttachment(
      $_SESSION['user_id'],
      'upload',
      $attachment['attachmentId'],
      "En Ticket #$ticketId"
    );

    RedirectHelper::success(
      'ticket?id=' . $ticketId,
      $attachment['success']
    );
  }

  public static function attachmentDownloadGet()
  {
    SessionController::requireLogin();

    $attachmentId = (int)$_GET['id'];
    $ticketId = (int)$_GET['ticket_id'];

    $attachment = AttachmentsController::download($attachmentId);

    if ($attachment['error']) {
      RedirectHelper::error(
        'ticket?id=' . $ticketId,
        $attachment['error']
      );
    }

    AuditLogsController::logAttachment(
      $_SESSION['user_id'],
      'download',
      $attachmentId,
      "De Ticket #$ticketId"
    );
  }
}
