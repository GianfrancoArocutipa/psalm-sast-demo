<?php
declare(strict_types=1);

/**
 * FIXED VERSION.
 * Path traversal removed: the user never controls a path, only an ID
 * that is validated against a strict pattern and mapped to a file
 * inside the reports directory.
 */

$id = $_GET['id'] ?? '';

// Only accept a strict identifier format (e.g. RPT-2026-00042).
// Anything containing "../", slashes or unexpected characters is
// rejected here, so the traversal is impossible past this point.
if (!is_string($id) || !preg_match('/^RPT-\d{4}-\d{5}$/', $id)) {
    http_response_code(400);
    exit('Invalid report id');
}

/**
 * Psalm's taint engine cannot reason about regular expressions, so it
 * still sees $id as tainted (a classic SAST false positive). Since the
 * regex above provably restricts the value to [A-Z0-9-], we tell the
 * analyzer this assignment is safe for file operations:
 *
 * @psalm-taint-escape file
 */
$path = '/var/app/reports/' . $id . '.pdf';

if (!is_file($path)) {
    http_response_code(404);
    exit('Report not found');
}

header('Content-Type: application/pdf');
readfile($path);
