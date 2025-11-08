<?php
require __DIR__.'/../config/db.php';
require __DIR__.'/_layout.php';

$agreements = $pdo->query("
  SELECT a.id, s.first_name, s.last_name, p.name AS plan_name
  FROM agreements a
  JOIN students s ON s.id=a.student_id
  JOIN plans p ON p.id=a.plan_id
  WHERE a.status='active'
  ORDER BY a.id DESC
")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $pdo->beginTransaction();
  try {
    $stmt = $pdo->prepare("INSERT INTO payments(agreement_id,amount_cents,paid_date,method,note) VALUES(?,?,?,?,?)");
    $stmt->execute([
      $_POST['agreement_id'],
      (int)$_POST['amount_cents'],
      $_POST['paid_date'],
      $_POST['method'],
      $_POST['note'] ?? null
    ]);

    $q = $pdo->prepare("SELECT interval_enum
                        FROM plans WHERE id=(SELECT plan_id FROM agreements WHERE id=?)");
    $q->execute([$_POST['agreement_id']]);
    $interval = $q->fetchColumn();

    $bump = ($interval === 'annual') ? '1 YEAR' : '1 MONTH';
    $pdo->prepare("UPDATE agreements SET next_due_date = DATE_ADD(next_due_date, INTERVAL $bump) WHERE id=?")
        ->execute([$_POST['agreement_id']]);

    $pdo->commit();
  } catch (Throwable $e) {
    $pdo->rollBack();
    error_log($e->getMessage());
  }
  header('Location: /payments.php'); exit;
}

$payments = $pdo->query("
  SELECT py.*, s.first_name, s.last_name
  FROM payments py
  JOIN agreements a ON a.id=py.agreement_id
  JOIN students s ON s.id=a.student_id
  ORDER BY py.paid_date DESC, py.id DESC
")->fetchAll();

header_html('Payments');
?>
<h3>Record Payment</h3>
<form method="post" class="row g-3 mb-4">
  <div class="col-md-3">
    <select required name="agreement_id" class="form-select">
      <option value="">Agreement...</option>
      <?php foreach ($agreements as $ag): ?>
        <option value="<?=$ag['id']?>"><?=htmlspecialchars("#{$ag['id']} — {$ag['first_name']} {$ag['last_name']} — {$ag['plan_name']}")?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2"><input required type="number" name="amount_cents" class="form-control" placeholder="Amount (cents)"></div>
  <div class="col-md-2"><input required type="date" name="paid_date" class="form-control" value="<?=date('Y-m-d')?>"></div>
  <div class="col-md-2">
    <select name="method" class="form-select">
      <option>cash</option><option>card</option><option>ach</option><option>other</option>
    </select>
  </div>
  <div class="col-md-2"><input name="note" class="form-control" placeholder="Note"></div>
  <div class="col-md-1"><button class="btn btn-primary w-100">Save</button></div>
</form>

<h3>Recent Payments</h3>
<table class="table table-hover">
  <thead><tr><th>ID</th><th>Student</th><th>Amount</th><th>Paid</th><th>Method</th><th>Note</th></tr></thead>
  <tbody>
  <?php foreach ($payments as $p): ?>
    <tr>
      <td><?=$p['id']?></td>
      <td><?=htmlspecialchars($p['first_name'].' '.$p['last_name'])?></td>
      <td>$<?=number_format($p['amount_cents']/100,2)?></td>
      <td><?=$p['paid_date']?></td>
      <td><?=$p['method']?></td>
      <td><?=htmlspecialchars($p['note'])?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php footer_html(); ?>
