<?php

use app\helpers\PermissionHelper;
use app\helpers\SecurityHelper;

?>

<?php if (PermissionHelper::can('comment-add')): ?>
  <form method="POST" action="comment-add">

    <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
    
    <?= SecurityHelper::csrfField(); ?>

    <textarea
      name="comment"
      id="comment"
      rows="4"
      placeholder="Escribe un comentario..."
      required></textarea>

    <button type="submit" class="button">
      Agregar comentario
    </button>

  </form>
<?php endif; ?>