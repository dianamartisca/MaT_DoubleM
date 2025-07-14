<?php
require_once __DIR__ . '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_file'])) {
    $file = $_FILES['import_file']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));

    if ($ext === 'csv') {
        $handle = fopen($file, "r");
        $header = fgetcsv($handle);
        $expected = ['id', 'denumire', 'categorie', 'cantitate'];
        if ($header !== $expected) {
            fclose($handle);
            echo "Header CSV invalid! Trebuie să fie: " . implode(',', $expected);
            exit;
        }
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $stmt = $conn->prepare("INSERT INTO piese (id, denumire, categorie, cantitate) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE denumire=VALUES(denumire), categorie=VALUES(categorie), cantitate=VALUES(cantitate)");
            $stmt->execute([
                $data['id'],
                $data['denumire'],
                $data['categorie'],
                $data['cantitate']
            ]);
        }
        fclose($handle);
        echo "Import CSV realizat!";
    } elseif ($ext === 'json') {
        $json = file_get_contents($file);
        $rows = json_decode($json, true);
        foreach ($rows as $data) {
            $stmt = $conn->prepare("INSERT INTO piese (id, denumire, categorie, cantitate) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE denumire=VALUES(denumire), categorie=VALUES(categorie), cantitate=VALUES(cantitate)");
            $stmt->execute([
                $data['id'],
                $data['denumire'],
                $data['categorie'],
                $data['cantitate']
            ]);
        }
        echo "Import JSON realizat!";
    } else {
        echo "Format fișier invalid!";
    }
} else {
    echo "Niciun fișier încărcat!";
}
