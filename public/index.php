<?php
require __DIR__.'/../config/db.php';
require __DIR__.'/_layout.php';
header_html('Dashboard');

$stmt = $pdo->prepare("
  SELECT a.id, s.first_name, s.last_name, p.name AS plan_name, a.next_due_date
  FROM agreements a
  JOIN students s ON s.id=a.student_id
  JOIN plans p ON p.id=a.plan_id
  WHERE a.status='active'
    AND a.next_due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
  ORDER BY a.next_due_date ASC
");
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<h3>Upcoming Renewals (next 30 days)</h3>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Student</th><th>Plan</th><th>Next Due</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['id'])?></td>
      <td><?=htmlspecialchars($r['first_name'].' '.$r['last_name'])?></td>
      <td><?=htmlspecialchars($r['plan_name'])?></td>
      <td><?=htmlspecialchars($r['next_due_date'])?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php footer_html(); ?>
