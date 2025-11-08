<?php
require __DIR__.'/../config/db.php';
require __DIR__.'/_layout.php';
$rows = $pdo->query("
  SELECT DATE_FORMAT(paid_date,'%Y-%m') AS ym, SUM(amount_cents) AS cents
  FROM payments GROUP BY ym ORDER BY ym DESC
")->fetchAll();
header_html('Revenue by Month');
?>
<h3>Revenue by Month</h3>
<table class="table table-bordered">
  <thead><tr><th>Month</th><th>Total</th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr><td><?=htmlspecialchars($r['ym'])?></td>
        <td>$<?=number_format($r['cents']/100,2)?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php footer_html(); ?>
