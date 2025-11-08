<?php
function header_html($title='Admin') {
echo <<<HTML
<!doctype html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title}</title>
<link rel="stylesheet" href="/assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4">
<nav class="mb-4 d-flex gap-2 flex-wrap">
  <a class="btn btn-outline-primary" href="/index.php">Dashboard</a>
  <a class="btn btn-outline-secondary" href="/students.php">Students</a>
  <a class="btn btn-outline-secondary" href="/agreements.php">Agreements</a>
  <a class="btn btn-outline-secondary" href="/payments.php">Payments</a>
  <a class="btn btn-outline-secondary" href="/revenue.php">Revenue</a>
  <a class="btn btn-outline-dark" href="/export_csv.php">Export CSV</a>
</nav>
<div class="container">
HTML;
}
function footer_html() {
echo "</div></body></html>";
}
