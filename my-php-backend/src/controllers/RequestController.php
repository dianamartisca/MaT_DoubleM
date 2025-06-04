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

    public function respond()
    {
        $id = $_POST['id'] ?? null;
        $response = $_POST['response'] ?? null;

        if (!$id || !$response) {
            echo json_encode(['error' => 'Date lipsă']);
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT email, name FROM requests WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['error' => 'Cerere inexistentă']);
            exit;
        }

        // PHPMailer
        require_once __DIR__ . '/../../vendor/autoload.php';
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dianamariamartisca@gmail.com';
            $mail->Password = 'dbjp owtn yfew ibaw';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('dianamariamartisca@gmail.com', 'Nume Expeditor');
            $mail->addAddress($row['email'], $row['name']);

            $mail->Subject = 'Răspuns la cererea ta';
            $mail->Body    = "Bună, {$row['name']}!\n\nRăspunsul la cererea ta:\n\n" . $response;

            $mail->send();

            // Daca emailul s-a trimis, salveaza răspunsul in DB
            $stmt = $conn->prepare("UPDATE requests SET response = ?, status = 'raspuns trimis' WHERE id = ?");
            $stmt->execute([$response, $id]);
            echo json_encode(['message' => 'Răspuns trimis pe email!']);
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo json_encode(['error' => 'Eroare la trimiterea emailului: ' . $mail->ErrorInfo]);
        }
        exit;
    }

    public function markDone($id)
{
    if ($this->requestModel->markAsDone($id)) {
        header('Content-Type: application/json');
        echo json_encode(["success" => true]);
    } else {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Eșec la actualizare"]);
    }
}
}
