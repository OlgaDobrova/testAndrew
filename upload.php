<?php

// команда на загрузку файла
// $ curl -F "file=@C:\Users\dell\Desktop\test\1.jpg" http://testAndrew/upload.php

header('Content-Type: text/plain');

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
    $delete_url = "$protocol://$host/delete.php?file=" . urlencode($file_name_new);
    echo "\n Link to download the file: \n";
    echo $download_url;
    echo "\n Link to delete the file: \n";
    echo $delete_url;
} else {
    echo 'Failed to upload file';
}