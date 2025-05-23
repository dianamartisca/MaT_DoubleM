<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Stock.php';

class StockController {
    private $stock;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->stock = new Stock($db);
    }

    public function getAll() {
        $stocks = $this->stock->getAllStocks();
        echo json_encode($stocks);
    }

    public function update() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']) || !isset($data['cantitate'])) {
            http_response_code(400);
            echo json_encode(["error" => "Lipsesc datele necesare."]);
            return;
        }

        $success = $this->stock->updateStock($data['id'], $data['cantitate']);

        if ($success) {
            echo json_encode(["message" => "Stoc actualizat cu succes."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Eroare la actualizarea stocului."]);
        }
    }


  public function add() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['denumire'], $data['categorie'], $data['cantitate'])) {
        http_response_code(400);
        echo json_encode(["error" => "Lipsesc datele necesare."]);
        return;
    }

    $success = $this->stock->addStock($data['denumire'], $data['categorie'], $data['cantitate']);

    if ($success) {
        echo json_encode(["message" => "Piesă adăugată cu succes."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Eroare la adăugare."]);
    }
}
}