<?php
require __DIR__.'/../config/db.php';
require __DIR__.'/_layout.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $pdo->prepare("INSERT INTO agreements(student_id,plan_id,next_due_date) VALUES(?,?,?)")
      ->execute([$_POST['student_id'], $_POST['plan_id'], $_POST['next_due_date']]);
  header('Location: /agreements.php'); exit;
}

$students = $pdo->query("SELECT id, first_name, last_name FROM students ORDER BY first_name")->fetchAll();
$plans = $pdo->query("SELECT id, name FROM plans WHERE active=1 ORDER BY name")->fetchAll();
$agreements = $pdo->query("
  SELECT a.*, s.first_name, s.last_name, p.name AS plan_name
  FROM agreements a
  JOIN students s ON s.id=a.student_id
  JOIN plans p ON p.id=a.plan_id
  ORDER BY a.created_at DESC
")->fetchAll();

header_html('Agreements');
?>
<h3>Create Agreement</h3>
<form method="post" class="row g-3 mb-4">
  <div class="col-md-3">
    <select required name="student_id" class="form-select">
      <option value="">Student...</option>
      <?php foreach ($students as $s): ?>
        <option value="<?=$s['id']?>"><?=htmlspecialchars($s['first_name'].' '.$s['last_name'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <select required name="plan_id" class="form-select">
      <option value="">Plan...</option>
      <?php foreach ($plans as $p): ?>
        <option value="<?=$p['id']?>"><?=htmlspecialchars($p['name'])?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <input required type="date" name="next_due_date" class="form-control">
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary w-100">Create</button>
  </div>
</form>

<h3>All Agreements</h3>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Student</th><th>Plan</th><th>Next Due</th><th>Status</th></tr></thead>
  <tbody>
  <?php foreach ($agreements as $a): ?>
    <tr>
      <td><?=$a['id']?></td>
      <td><?=htmlspecialchars($a['first_name'].' '.$a['last_name'])?></td>
      <td><?=htmlspecialchars($a['plan_name'])?></td>
      <td><?=$a['next_due_date']?></td>
      <td><?=$a['status']?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php footer_html(); ?>
