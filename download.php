<?php

// пример команды на скачивание
// curl -o "C:\Users\dell\Desktop\test\6852f6d29404e.txt" "http://testAndrew/download.php?file=6852f6d29404e.txt"

$upload_dir = __DIR__ . '/uploads/';

if (!isset($_GET['file']) || empty($_GET['file'])) {
    die('Error: File parameter is required');
}

$file_name = basename($_GET['file']);
$file_path = $upload_dir . $file_name;

if (!file_exists($file_path)) {
    die('Error: File not found');
}

header('Content-Description: File Transfer');
header('Content-Type: ' . mime_content_type($file_path));
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
exit;