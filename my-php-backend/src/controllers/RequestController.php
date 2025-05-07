<?php

require_once '../models/Request.php';
require_once '../helpers/response.php';
require_once '../config/database.php';

class RequestController {
    private $requestModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->requestModel = new Request($db);
    }

    public function createRequest($name, $email, $problemType, $dateRequested, $description, $images) {
        $this->requestModel->name = $name;
        $this->requestModel->email = $email;
        $this->requestModel->problem_type = $problemType;
        $this->requestModel->date_requested = $dateRequested;
        $this->requestModel->description = $description;
        $this->requestModel->images = $images;

        if ($this->requestModel->createRequest()) {
            sendResponse(201, "Request created successfully.");
        } else {
            sendResponse(500, "Failed to create request.");
        }
    }

    public function getRequests() {
        $requests = $this->requestModel->getRequests();
        if ($requests) {
            $data = $requests->fetchAll(PDO::FETCH_ASSOC);
            sendResponse(200, $data);
        } else {
            sendResponse(404, "No requests found.");
        }
    }

    public function respondToRequest($requestId, $response) {
        $this->requestModel->id = $requestId;
        $this->requestModel->response = $response;

        if ($this->requestModel->respondToRequest()) {
            sendResponse(200, "Response updated successfully.");
        } else {
            sendResponse(500, "Failed to update response.");
        }
    }
}
?>