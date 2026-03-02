<table>
  <thead>
    <tr>
      <th>Título</th>
      <th>Estado</th>
      <th>Prioridad</th>
      <th>Asignado a</th>
      <th>Fecha de creación</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets as $ticket): ?>
      <tr>
        <td><?= htmlspecialchars($ticket['title']) ?></td>
        <td><?= htmlspecialchars($ticket['status']) ?></td>
        <td><?= htmlspecialchars($ticket['priority']) ?></td>
        <td><?= empty($ticket['assigned_to_username'])
              ? 'Sin asignar'
              : htmlspecialchars($ticket['assigned_to_username']) ?></td>
        <td><?= htmlspecialchars($ticket['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>