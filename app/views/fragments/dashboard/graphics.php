<?php

use app\helpers\SecurityHelper;

$graphics = $statsDetails['graphics'] ?? [];

?>

<div class="graphics-container">

  <?php foreach ($graphics as $key => $config): ?>

    <div class="graphic-block">
      <h2><?= SecurityHelper::escapeXSS($config['label']) ?></h2>

      <div class="bar-chart">

        <?php if (!empty($distribution[$key])):

          $max = max(array_column($distribution[$key], 'total'));

          foreach ($distribution[$key] as $item):

            $percent = $max > 0 ? ($item['total'] / $max) * 100 : 0;
            $maskWidth = 100 - $percent;
        ?>

            <p>
              <?= SecurityHelper::escapeXSS($item[$config['field']]) ?>
              (<?= SecurityHelper::escapeXSS($item['total']) ?>)
            </p>

            <div class="bar-wrapper">
              <div class="bar-mask" style="width: <?= $maskWidth ?>%"></div>
            </div>

        <?php endforeach;
        endif; ?>

      </div>
    </div>

  <?php endforeach; ?>

</div>