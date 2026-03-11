<?php

use app\helpers\SecurityHelper;
?>
<div class="ticket-create-container">
  <form method="POST" class="ticket-create-form">

    <?= SecurityHelper::csrfField(); ?>

    <h2>Nuevo ticket</h2>

    <?php if (!empty($_SESSION['ticket_error'])): ?>
      <div class="error-message">
        <?= SecurityHelper::escapeXSS($_SESSION['ticket_error']) ?>
      </div>
      <?php unset($_SESSION['ticket_error']); ?>
    <?php endif; ?>

    <label>
      Título *
      <input
        type="text"
        name="title"
        required
        placeholder="Título del ticket">
    </label>
    <label>
      Descripción
      <textarea
        name="description"
        rows="4"
        placeholder="Describe el problema o solicitud">
        </textarea>
    </label>
    <label>
      Prioridad
      <select name="priority">
        <option value="low">Baja</option>
        <option value="medium" selected>Media</option>
        <option value="high">Alta</option>
        <option value="critical">Crítica</option>
      </select>
    </label>
    <label>
      Categoría
      <input
        type="text"
        name="category"
        placeholder="bug, feature, security…">
    </label>
    <label>
      Asignar a
      <select name="assigned_to">
        <option value="">Sin asignar</option>
        <option value="1">admin</option>
        <option value="2">technician</option>
        <option value="3">reader</option>
      </select>
    </label>
    <button class="button" type="submit">Crear ticket</button>
  </form>
</div>