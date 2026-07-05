<?php
declare(strict_types=1);

/**
 * VULNERABLE ENDPOINT — do not deploy.
 * Searches vehicles by license plate in a workshop database.
 *
 * Flaw: user input from $_GET flows directly into an SQL string
 * (SQL Injection) and is later echoed back without encoding (XSS).
 */

/**
 * Thin wrapper around PDO::query.
 * The annotation below tells Psalm that anything reaching $sql is an
 * SQL sink — this mirrors how real projects annotate their own DB
 * layer so the analyzer knows about custom sinks.
 *
 * @psalm-taint-sink sql $sql
 */
function runQuery(PDO $pdo, string $sql): PDOStatement|false
{
    return $pdo->query($sql);
}

$pdo = new PDO('mysql:host=localhost;dbname=workshop', 'app', 'secret');

$term = $_GET['q'];                                            // tainted source

$stmt = runQuery(
    $pdo,
    "SELECT plate, owner, model FROM vehicles WHERE plate LIKE '%$term%'"
);                                                             // TaintedSql sink

echo "<h2>Results for: " . $term . "</h2>";                    // TaintedHtml sink

if ($stmt !== false) {
    foreach ($stmt as $row) {
        echo "<li>{$row['plate']} — {$row['owner']} ({$row['model']})</li>";
    }
}
