<?php
declare(strict_types=1);

/**
 * FIXED VERSION.
 * - SQL Injection removed with a prepared statement (data never
 *   touches the SQL grammar).
 * - XSS removed by encoding every value before it reaches HTML.
 */

$pdo = new PDO('mysql:host=localhost;dbname=workshop', 'app', 'secret', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$term = (string) ($_GET['q'] ?? '');

$stmt = $pdo->prepare(
    'SELECT plate, owner, model FROM vehicles WHERE plate LIKE :term'
);
$stmt->execute([':term' => '%' . $term . '%']);

$safeTerm = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
echo "<h2>Results for: {$safeTerm}</h2>";

foreach ($stmt as $row) {
    $plate = htmlspecialchars((string) $row['plate'], ENT_QUOTES, 'UTF-8');
    $owner = htmlspecialchars((string) $row['owner'], ENT_QUOTES, 'UTF-8');
    $model = htmlspecialchars((string) $row['model'], ENT_QUOTES, 'UTF-8');
    echo "<li>{$plate} — {$owner} ({$model})</li>";
}
