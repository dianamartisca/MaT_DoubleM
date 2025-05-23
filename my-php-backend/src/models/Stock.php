<?php

class Stock {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllStocks() {
        $query = "SELECT * FROM piese";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStock($id, $cantitate) {
        $query = "UPDATE piese SET cantitate = :cantitate WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cantitate', $cantitate);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function addStock($denumire, $categorie, $cantitate) {
    $query = "INSERT INTO piese (denumire, categorie, cantitate) VALUES (:d, :c, :q)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':d', $denumire);
    $stmt->bindParam(':c', $categorie);
    $stmt->bindParam(':q', $cantitate);
    return $stmt->execute();
}
}