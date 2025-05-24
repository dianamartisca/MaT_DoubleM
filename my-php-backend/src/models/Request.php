<?php
class Request {
    private $conn;
    private $table = 'requests';

    public $id;
    public $name;
    public $email;
    public $problem_type;
    public $date_requested;
    public $description;
    public $images;
    public $response;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createRequest() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, email, problem_type, date_requested, description, images) 
                  VALUES (:name, :email, :problem_type, :date_requested, :description, :images)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':problem_type', $this->problem_type);
        $stmt->bindParam(':date_requested', $this->date_requested);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':images', $this->images);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getRequests() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function respondToRequest() {
        $query = "UPDATE " . $this->table . " SET response = :response WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':response', $this->response);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    //pt aprobare
    public function approveRequest($id) {
    $query = "UPDATE requests SET status = 'aprobata' WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
    public function rejectRequest($id) {
    $query = "UPDATE requests SET status = 'respinsa' WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
}
?>