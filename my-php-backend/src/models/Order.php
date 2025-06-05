<?php

require_once __DIR__ . '/../config/database.php';

class Order
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM comenzi_furnizori ORDER BY data_comanda DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($produs, $furnizor, $cantitate, $data_comanda)
    {
        //am pus try catch ca nu pune nimk
        try {
            $sql = "INSERT INTO comenzi_furnizori (produs, furnizor, cantitate, data_comanda)
                VALUES (:produs, :furnizor, :cantitate, :data_comanda)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':produs' => $produs,
                ':furnizor' => $furnizor,
                ':cantitate' => $cantitate,
                ':data_comanda' => $data_comanda
            ]);
        } catch (PDOException $e) {
            error_log("Eroare inserare: " . $e->getMessage());
            return false;
        }
    }
}
