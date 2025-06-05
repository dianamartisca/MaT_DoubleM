<?php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/Review.php';

class ReviewController
{
    public function add()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $text = $_POST['text'] ?? '';

        if (!$name || !$email || !$text) {
            echo json_encode(['error' => 'Toate câmpurile sunt obligatorii!']);
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $reviewModel = new Review($conn);

        $ok = $reviewModel->addReview($name, $email, $text);

        if ($ok) {
            echo json_encode(['message' => 'Review salvat cu succes!']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la salvarea review-ului!']);
        }
    }

    public function getAll()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $reviewModel = new Review($conn);

        $reviews = $reviewModel->getAllReviews();
        echo json_encode(['reviews' => $reviews]);
    }
}