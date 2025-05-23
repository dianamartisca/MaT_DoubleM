<?php

require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private $model;

    public function __construct() {
        $this->model = new Order();
    }

    public function getOrders() {
        header('Content-Type: application/json');
        $orders = $this->model->getAll();
        echo json_encode($orders);
    }

    public function addOrder() {

    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    error_log("Date primite: " . print_r($data, true));

    if (!isset($data['produs'], $data['furnizor'], $data['cantitate'], $data['data_comanda'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Date lipsă']);
        return;
    }

    $success = $this->model->insert(
        $data['produs'],
        $data['furnizor'],
        (int)$data['cantitate'],
        $data['data_comanda']
    );

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        error_log("Inserare esuata în model.");
        http_response_code(500);
        echo json_encode(['error' => 'Inserare esuata']);
    }
}
}