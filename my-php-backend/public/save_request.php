<?php
require_once '../src/config/database.php';
require_once '../src/models/Request.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Database connection failed.");
}

$request = new Request($db);

$request->name = $_POST['name'];
$request->email = $_POST['email'];
$request->problem_type = $_POST['problem_type'];
$date_requested = $_POST['date_requested'];
$date_requested = str_replace('ora', '', $date_requested); 
$date_requested = DateTime::createFromFormat('d.m.Y H:i', trim($date_requested));
if ($date_requested) {
    $request->date_requested = $date_requested->format('Y-m-d H:i:s');
} else {
    die("Invalid date format.");
}
$request->description = $_POST['description'];
$uploaded_images = [];
if (!empty($_FILES['images']['name'][0])) {
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_images[] = $target_file;
        }
    }
}
$request->images = implode(',', $uploaded_images);

if ($request->createRequest()) {
    echo "Cererea a fost trimisă cu succes!";
} else {
    echo "Eroare la salvarea cererii.";
}
?>