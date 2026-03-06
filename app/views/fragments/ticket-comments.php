<?php if (empty($comments)): ?>

  <p class="no-comments">No hay comentarios todavía.</p>

<?php else: ?>

  <div class="comments-timeline">

    <?php foreach ($comments as $comment): ?>

      <div class="comment-item">

        <div class="comment-meta">
          <span class="comment-author">
            <?= htmlspecialchars($comment['username']) ?>
          </span>

          <span class="comment-date">
            <?= htmlspecialchars($comment['created_at']) ?>
          </span>
        </div>

        <div class="comment-body">
          <?= nl2br(htmlspecialchars($comment['comment'])) ?>
        </div>

      </div>

    <?php endforeach; ?>

  </div>

<?php endif; ?>