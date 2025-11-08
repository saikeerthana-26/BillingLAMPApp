
<?php
require __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("
  SELECT s.email, s.first_name, s.last_name, p.name AS plan_name, a.next_due_date
  FROM agreements a
  JOIN students s ON s.id=a.student_id
  JOIN plans p ON p.id=a.plan_id
  WHERE a.status='active' AND a.next_due_date = DATE_ADD(CURDATE(), INTERVAL 3 DAY)
");
$stmt->execute();
$dueSoon = $stmt->fetchAll();

foreach ($dueSoon as $d) {
  echo "[REMINDER] {$d['email']} — {$d['first_name']} {$d['last_name']} plan {$d['plan_name']} due on {$d['next_due_date']}\n";
}
