<?php
declare(strict_types=1);

/**
 * FIXED VERSION.
 * Path traversal removed: the user never controls a path, only an ID
 * that is validated and mapped to a file inside the reports directory.
 */

$id = $_GET['id'] ?? '';

// Only accept a strict identifier format (e.g. RPT-2026-00042)
if (!preg_match('/^RPT-\d{4}-\d{5}$/', $id)) {
    http_response_code(400);
    exit('Invalid report id');
}

$path = '/var/app/reports/' . $id . '.pdf';

if (!is_file($path)) {
    http_response_code(404);
    exit('Report not found');
}

header('Content-Type: application/pdf');
readfile($path);
