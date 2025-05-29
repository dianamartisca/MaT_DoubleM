<?php
require_once __DIR__ . '/../database.php'; 

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file'])) {
    $file = $_FILES['import_file']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));

    if ($ext === 'csv') {
        $handle = fopen($file, "r");
        // Citim headerul
        $header = fgetcsv($handle);
        $expected = ['name', 'email', 'problem_type', 'date_requested', 'description', 'images', 'response', 'status'];
        if ($header !== $expected) {
            fclose($handle);
            echo "Header CSV invalid! Trebuie să fie: " . implode(',', $expected);
            exit;
        }
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $stmt = $conn->prepare("INSERT INTO requests (name, email, problem_type, date_requested, description, images, response, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['problem_type'],
                $data['date_requested'],
                $data['description'],
                $data['images'],
                $data['response'],
                $data['status']
            ]);
        }
        fclose($handle);
        echo "Import CSV realizat!";
    } elseif ($ext === 'json') {
        $json = file_get_contents($file);
        $rows = json_decode($json, true);
        foreach ($rows as $data) {
            $stmt = $conn->prepare("INSERT INTO requests (name, email, problem_type, date_requested, description, images, response, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['problem_type'],
                $data['date_requested'],
                $data['description'],
                $data['images'],
                $data['response'],
                $data['status']
            ]);
        }
        echo "Import JSON realizat!";
    } else {
        echo "Format fișier invalid!";
    }
} else {
    echo "Niciun fișier încărcat!";
}
?>