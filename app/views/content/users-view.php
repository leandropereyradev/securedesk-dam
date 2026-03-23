<?php

use app\helpers\UsersListViewHelper;

$users = view('users', []);

$filters = UsersListViewHelper::getFilters();
$columns = UsersListViewHelper::getColumns();

$rows = $users;
$ref = "users";

$selectedFilters = view('filters', []);

$showSearch = false;
?>

<div class="list-container">
  <div class="title">
    <h1>Usuarios</h1>
  </div>

  <?php require_once ROOT . "app/views/fragments/filter-form.php"; ?>

  <?php if (empty($users)): ?>
    <p>No hay usuarios disponibles.</p>
  <?php else: ?>

    <?php require_once ROOT . "app/views/fragments/table.php"; ?>
  <?php endif; ?>
</div>