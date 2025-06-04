<?php
require_once dirname(__DIR__) . '/config/database.php';

class ReviewController {
    public function add() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $text = $_POST['text'] ?? '';

        if (!$name || !$email || !$text) {
            echo json_encode(['error' => 'Toate câmpurile sunt obligatorii!']);
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("INSERT INTO reviews (name, email, text) VALUES (?, ?, ?)");
        $ok = $stmt->execute([$name, $email, $text]);

        if ($ok) {
            echo json_encode(['message' => 'Review salvat cu succes!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la salvarea review-ului!']);
        }
    }

    public function getAll() {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->query("SELECT name, text FROM reviews ORDER BY id DESC");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['reviews' => $reviews]);
    }
}