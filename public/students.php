<?php
require __DIR__.'/../config/db.php';
require __DIR__.'/_layout.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $pdo->prepare("INSERT INTO students(first_name,last_name,email) VALUES(?,?,?)")
      ->execute([$_POST['first_name'], $_POST['last_name'], $_POST['email']]);
  header('Location: /students.php'); exit;
}
$students = $pdo->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
header_html('Students');
?>
<h3>Add Student</h3>
<form method="post" class="row g-3 mb-4">
  <div class="col-md-3"><input required class="form-control" name="first_name" placeholder="First name"></div>
  <div class="col-md-3"><input required class="form-control" name="last_name" placeholder="Last name"></div>
  <div class="col-md-4"><input required type="email" class="form-control" name="email" placeholder="Email"></div>
  <div class="col-md-2"><button class="btn btn-primary w-100">Create</button></div>
</form>

<h3>All Students</h3>
<table class="table table-bordered">
  <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Created</th></tr></thead>
  <tbody>
  <?php foreach ($students as $s): ?>
    <tr>
      <td><?=$s['id']?></td>
      <td><?=htmlspecialchars($s['first_name'].' '.$s['last_name'])?></td>
      <td><?=htmlspecialchars($s['email'])?></td>
      <td><?=$s['created_at']?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php footer_html(); ?>
