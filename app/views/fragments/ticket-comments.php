<?php

use app\helpers\SecurityHelper;

if (empty($comments)): ?>

  <p class="no-comments">No hay comentarios todavía.</p>

<?php else: ?>

  <div class="comments-timeline">

    <?php foreach ($comments as $comment): ?>

      <div class="comment-item">

        <div class="comment-meta">
          <span class="comment-author">
            <?= SecurityHelper::escapeXSS($comment['username']) ?>
          </span>

          <span class="comment-date">
            <?= SecurityHelper::escapeXSS($comment['created_at']) ?>
          </span>
        </div>

        <div class="comment-body">
          <?= nl2br(SecurityHelper::escapeXSS($comment['comment'])) ?>
        </div>

      </div>

    <?php endforeach; ?>

  </div>

<?php endif; ?>