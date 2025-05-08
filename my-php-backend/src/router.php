<?php
require_once 'controllers/RequestController.php';

function routeRequest($method, $uri) {
    $uri = explode('?', $uri)[0];
    $segments = explode('/', $uri);
    error_log("Request URI: " . $uri);
    error_log("Segments: " . print_r($segments, true));
    if (in_array('requests', $segments)) {
        $requestController = new RequestController();

        switch ($method) {
            case 'POST':
                $name = $_POST['name'];
                $email = $_POST['email'];
                $problemType = $_POST['problem_type'];
                $date_requested = $_POST['date_requested'];
                $date_requested = str_replace('ora', '', $date_requested);
                $date_requested = DateTime::createFromFormat('d.m.Y H:i', trim($date_requested));
                if ($date_requested) {
                    $dateRequested = $date_requested->format('Y-m-d H:i:s');
                } else {
                    sendError("Invalid date format.", 400);
                }
                $description = $_POST['description'];

                $uploaded_images = [];
                if (!empty($_FILES['images']['name'][0])) {
                    $upload_dir = '../uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }

                    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                        $file_name = basename($_FILES['images']['name'][$key]);
                        $target_file = $upload_dir . $file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $uploaded_images[] = $target_file;
                        }
                    }
                }
                $images = implode(',', $uploaded_images);

                $requestController->createRequest($name, $email, $problemType, $dateRequested, $description, $images);
                break;

            case 'GET':
                $requestController->getRequests();
                break;

            default:
                sendError("Method not allowed.", 405);
                break;
        }
    } else {
        sendError("Endpoint not found.", 404);
    }
}
?>