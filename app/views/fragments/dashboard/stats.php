<?php

use app\helpers\SecurityHelper;
?>
<div class="stats-container">
  <div class="tickets-kpi">
    <h2>Tickets</h2>
    <?php foreach ($statsDetails['stats'] as $field => $label): ?>

      <p><strong> <?= SecurityHelper::escapeXSS($label) ?> </strong>
        <?= SecurityHelper::escapeXSS($stats[$field]) ?>
      </p>
    <?php endforeach; ?>
  </div>

  <div class="priorities-kpi">
    <h2>Prioridad</h2>
    <?php foreach ($statsDetails['priorities'] as $field => $label): ?>

      <p><strong> <?= SecurityHelper::escapeXSS($label) ?> </strong>
        <?= SecurityHelper::escapeXSS($stats[$field]) ?>
      </p>
    <?php endforeach; ?>
  </div>
</div>