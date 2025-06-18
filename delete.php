<?php

// команда на удаление файла
// curl -X DELETE "http://testAndrew/delete.php?file=6852f6d29404e.txt"

$upload_dir = 'uploads/';

if (!isset($_GET['file'])) {
  die(json_encode(['status' => 'error', 'message' => 'No file specified']));
}

$file_name = basename($_GET['file']);
$file_path = $upload_dir . $file_name;

if (!file_exists($file_path)) {
  die(json_encode(['status' => 'error', 'message' => 'File not found']));
}

if (unlink($file_path)) {
  die(json_encode(['status' => 'success', 'message' => "File $file_name deleted"]));
} else {
  die(json_encode(['status' => 'error', 'message' => 'Delete failed']));
}
?>