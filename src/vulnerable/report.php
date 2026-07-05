<?php
declare(strict_types=1);

/**
 * VULNERABLE ENDPOINT — do not deploy.
 * Serves a diagnostic PDF report for a given vehicle.
 *
 * Flaw: the file name comes straight from the query string, so
 * ?file=../../etc/passwd walks out of the reports directory
 * (Path Traversal / Arbitrary File Read).
 */

$file = $_GET['file'];                        // tainted source

header('Content-Type: application/pdf');
readfile('/var/app/reports/' . $file);        // TaintedFile sink
