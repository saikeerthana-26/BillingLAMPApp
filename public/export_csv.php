<?php
// export_csv.php
ini_set('display_errors','0');                  // don’t leak warnings into CSV
error_reporting(E_ERROR | E_PARSE);             // keep CSV clean

require __DIR__ . '/../config/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="payments.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['id','agreement_id','amount_cents','paid_date','method','note','created_at'], ',', '"', "\\");

$stmt = $pdo->query("SELECT id,agreement_id,amount_cents,paid_date,method,note,created_at
                     FROM payments ORDER BY id DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($out, $row, ',', '"', "\\");
}
fclose($out);
