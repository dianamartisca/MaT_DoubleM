<?php

require_once dirname(__DIR__) . '/models/Request.php';
require_once dirname(__DIR__) . '/helpers/response.php';
require_once dirname(__DIR__) . '/config/database.php';

class RequestController
{
    private $requestModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->requestModel = new Request($db);
    }

    public function createRequest($name, $email, $problemType, $dateRequested, $description, $images)
    {
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

    public function getRequests()
    {
        $requests = $this->requestModel->getRequests();
        if ($requests) {
            $data = $requests->fetchAll(PDO::FETCH_ASSOC);
            sendResponse(200, $data);
        } else {
            sendResponse(404, "No requests found.");
        }
    }

    public function respondToRequest($requestId, $response)
    {
        $this->requestModel->id = $requestId;
        $this->requestModel->response = $response;

        if ($this->requestModel->respondToRequest()) {
            sendResponse(200, "Response updated successfully.");
        } else {
            sendResponse(500, "Failed to update response.");
        }
    }

    public function approve($id)
    {
        if ($this->requestModel->approveRequest($id)) {
            echo json_encode(["message" => "Cerere aprobată"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Eroare la aprobare"]);
        }
    }

    public function reject($id)
    {
        if ($this->requestModel->rejectRequest($id)) {
            echo json_encode(["message" => "Cerere respinsă"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Eroare la respingere"]);
        }
    }

    public function delete($id)
    {
        if ($this->requestModel->deleteRequest($id)) {
            echo json_encode(["message" => "Cerere ștearsă"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Eroare la ștergere"]);
        }
    }

    public function resetStatus($id)
    {
        if ($this->requestModel->resetRequestStatus($id)) {
            echo json_encode(["message" => "Status resetat"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Eroare la resetarea statusului"]);
        }
    }
}
