<?php
$filename = basename($_GET['file']);
$filepath = __DIR__ . '/../uploads/' . $filename;

if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: inline; filename=\"$filename\""); //inline ca sa incerce sa ii dea preview
    readfile($filepath);
    exit;
} else {
    http_response_code(404);
    echo "Fisierul nu a fost gasit.";
}
