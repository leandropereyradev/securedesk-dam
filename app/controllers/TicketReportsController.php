<?php

namespace app\controllers;

use app\helpers\DateHelper;
use Dompdf\Dompdf;

class TicketReportsController
{
  public static function getReportData(int $ticketId): ?array
  {
    $ticket = TicketsController::getTicketById($ticketId);

    if (!$ticket) {
      return null;
    }

    $comments =   TicketCommentsController::listAll($ticketId);
    $history =  TicketHistoryController::listHistory($ticketId);
    $attachments = AttachmentsController::getAttachmentsByTicket($ticketId);

    $report = [
      'generated_by' => $_SESSION['username'],
      'generated_at' => DateHelper::utcToMadrid(gmdate('Y-m-d H:i')),
      'ticket' => $ticket,
      'comments' => $comments,
      'history' => $history,
      'attachments' => $attachments
    ];

    return $report;
  }

  public static function exportCsv(array $filters = []): void
  {

    $tickets = TicketsController::listAll($filters);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tickets.csv"');

    $file = fopen('php://output', 'w');

    fputcsv($file, [
      'id',
      'title',
      'status',
      'priority',
      'assigned_to',
      'created_at'
    ]);

    foreach ($tickets as $ticket) {

      fputcsv($file, [
        $ticket['id'],
        $ticket['title'],
        $ticket['status'],
        $ticket['priority'],
        $ticket['assigned_to_username'] ?? 'Sin asignar',
        $ticket['created_at']
      ]);
    }

    fclose($file);

    exit;
  }

  public static function exportPdf(array $report): void
  {
    $dompdf = new Dompdf();

    $ticketId = $report['ticket']['id'];

    $cssPath = __DIR__ . '/../../public/assets/styles/pdf.css';
    $css = file_get_contents($cssPath);

    ob_start();
    $isPdf = true;
    require __DIR__ . '/../views/content/ticket-report-view.php';
    $html = ob_get_clean();

    $fullHtml = '<style>' . $css . '</style>' . $html;

    $dompdf->loadHtml($fullHtml);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->setBasePath(__DIR__ . '/../../public/');

    $dompdf->render();
    $dompdf->stream(
      "ticket-$ticketId.pdf",
      ['Attachment' => true]
    );

    exit;
  }
}
