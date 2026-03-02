<form method="GET" class="ticket-filters">
  <label>
    Estado:
    <select name="status">
      <option value="">Todos</option>
      <option value="new" <?= ($_GET['status'] ?? '') === 'new' ? 'selected' : '' ?>>Nuevo</option>
      <option value="in_process" <?= ($_GET['status'] ?? '') === 'in_process' ? 'selected' : '' ?>>En proceso</option>
      <option value="resolved" <?= ($_GET['status'] ?? '') === 'resolved' ? 'selected' : '' ?>>Resuelto</option>
    </select>
  </label>

  <label>
    Prioridad:
    <select name="priority">
      <option value="">Todas</option>
      <option value="low" <?= ($_GET['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Baja</option>
      <option value="medium" <?= ($_GET['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Media</option>
      <option value="high" <?= ($_GET['priority'] ?? '') === 'high' ? 'selected' : '' ?>>Alta</option>
      <option value="critical" <?= ($_GET['priority'] ?? '') === 'critical' ? 'selected' : '' ?>>Crítica</option>
    </select>
  </label>

  <button type="submit">Filtrar</button>
</form>