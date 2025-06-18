<?php

// команда на удаление файлов, загруженных более 1 часа
// curl "http://testAndrew/cleanup.php"

$upload_dir = 'uploads/';

$files = scandir($upload_dir);
$current_time = time();

foreach ($files as $file) {
  if ($file === '.' || $file === '..') continue;
  
  $file_id = pathinfo($file, PATHINFO_FILENAME);
  $upload_time = hexdec(substr($file_id, 0, 8));
  
  // если файл загружен более часа
  if ($current_time - $upload_time > 3600) {
      unlink($upload_dir . $file);
  }
}
?>