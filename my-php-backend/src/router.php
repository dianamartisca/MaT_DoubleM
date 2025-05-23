<?php
require_once 'controllers/RequestController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/StockController.php';
function routeRequest($method, $uri)
{



    $uri = explode('?', $uri)[0];
    $segments = explode('/', $uri);

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
    } 
    
    elseif (in_array('orders', $segments)) {
        $orderController = new OrderController();

        switch ($method) {
            case 'GET':
                $orderController->getOrders();
                break;
            case 'POST':
                $orderController->addOrder();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    } 
    
   elseif (in_array('piese', $segments)) {
    $controller = new StockController();

    if ($method == 'GET') {
        $controller->getAll();
    }
    elseif ($method == 'POST' && in_array('update', $segments)) {
        $controller->update();
    }
    elseif ($method == 'POST' && !in_array('update', $segments)) {
        $controller->add();
    }
    else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
}
 else {
        sendError("Endpoint not found.", 404);
    }
}
