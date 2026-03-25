<?php

$ticket = view('ticket', []);
$history = $ticket['history'] ?? [];
$comments = $ticket['comments'] ?? [];
$attachments = $ticket['attachments'] ?? [];
?>

<h1>Detalle del Ticket</h1>

<div class="ticket-details-container">
  <div>
    <?php require_once ROOT . "app/views/fragments/ticket-card.php"; ?>

    <?php require_once ROOT . "app/views/fragments/flash-messages.php"; ?>

    <?php require_once ROOT . "app/views/fragments/ticket-actions.php"; ?>

    <?php require_once ROOT . "app/views/fragments/ticket-attachments.php"; ?>

  </div>

  <div class="comments-section">
    <h3>Comentarios</h3>

    <?php require_once ROOT . "app/views/fragments/ticket-comment-form.php"; ?>

    <?php require_once ROOT . "app/views/fragments/ticket-comments.php"; ?>

    <?php require_once ROOT . "app/views/fragments/ticket-changes-history.php"; ?>

  </div>

</div>