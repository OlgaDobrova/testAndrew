<?php

// echo "<pre>";
//   var_dump($_FILES);
// echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Error: Only POST method is allowed');
}

if (!isset($_FILES['file'])) {
    die('Error: No file uploaded');
}

$file = $_FILES['file'];
$file_name = $file["name"];
$file_full_path = $file["full_path"];
$file_type = $file["type"];
$file_tmp_name = $file["tmp_name"];
$file_error = $file["error"];
$file_size = $file["size"];

$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

$allowed_types = ['jpg', 'jpeg', 'png', 'pdf', "txt"];
// 1 MiB = 1024 KiB (кибибайт) = 1024 * 1024 = 1 048 576 байт
$max_size = 512 * 1024 * 1024;

if (!in_array($file_extension, $allowed_types)) {
    die('Error: File type not allowed');
}

if ($file_size > $max_size) {
    die('Error: File is too large');
}

if ($file_error !== 0){
    die('Error: Something went wrong');
}

$upload_dir = __DIR__ . '/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$file_name_new = uniqid() . '.' . $file_extension;
$file_path = $upload_dir . $file_name_new;

if (move_uploaded_file($file_tmp_name, $file_path)) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $download_url = "$protocol://$host/download.php?file=" . urlencode($file_name_new);
    echo "Link to download the file: <br>";
    echo "<a href='$download_url'>$download_url</a><br>";
    $delete_url = "$protocol://$host/delete.php?file=" . urlencode($file_name_new);
    echo "Link to delete the file: <br>";
    echo "<a href='$delete_url'>$delete_url</a><br>";
} else {
    echo 'Failed to upload file';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>test</title>
  <link rel="stylesheet" href="assets/css/normalize.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="upload-container">
      <h1>Загрузите файл</h1>
      <form id="uploadForm" enctype="multipart/form-data" method="POST">
          <input type="file" name="file" id="fileInput" required>
          <button type="submit" name="submit">Загрузить</button>
      </form>
      <div id="result"></div>
  </div>
  <div class="">
    <p>
      команда на загрузку файла <br>
      curl -F "file=@C:\Users\dell\Desktop\test\1.jpg" http://testAndrew/upload.php
    </p>
    <p>
      после успешной загрузки файла сформируются ссылки на скачивание и удаление этого файла
    </p>
    <p>
      пример команды на скачивание <br>
      curl -o "C:\Users\dell\Desktop\test\6852f6d29404e.txt" "http://testAndrew/download.php?file=6852f6d29404e.txt"
    </p>
    <p>
      команда на удаление файла <br>
      curl -X DELETE "http://testAndrew/delete.php?file=6852f6d29404e.txt"
    </p>
    <p>
      команда на удаление файлов, загруженных более 1 часа <br>
      curl "http://testAndrew/cleanup.php"
    </p>
  </div>
</body>
</html>