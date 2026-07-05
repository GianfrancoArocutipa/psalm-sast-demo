<?php
declare(strict_types=1);

/**
 * VULNERABLE ENDPOINT — do not deploy.
 * Searches vehicles by license plate in a workshop database.
 *
 * Flaw: user input from $_GET flows directly into an SQL string
 * (SQL Injection) and is later echoed back without encoding (XSS).
 */

$pdo = new PDO('mysql:host=localhost;dbname=workshop', 'app', 'secret');

$term = $_GET['q'];                                            // tainted source

$stmt = $pdo->query(
    "SELECT plate, owner, model FROM vehicles WHERE plate LIKE '%$term%'"
);                                                             // TaintedSql sink

echo "<h2>Results for: " . $term . "</h2>";                    // TaintedHtml sink

foreach ($stmt as $row) {
    echo "<li>{$row['plate']} — {$row['owner']} ({$row['model']})</li>";
}
