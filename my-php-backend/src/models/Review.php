<?php
class Review
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllReviews()
    {
        $query = "SELECT * FROM reviews ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReview($name, $email, $text)
    {
        $query = "INSERT INTO reviews (name, email, text) VALUES (:name, :email, :text)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':text', $text);
        return $stmt->execute();
    }
}