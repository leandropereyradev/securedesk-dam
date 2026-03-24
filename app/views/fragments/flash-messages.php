<?php

use app\helpers\FlashHelper;

$types = ['error', 'success', 'warning', 'info'];

foreach ($types as $type):
  $message = FlashHelper::get($type);
  if ($message):
?>

    <div class="alert <?= $type ?>">
      <?= $message ?>
    </div>

<?php
  endif;
endforeach;
?>