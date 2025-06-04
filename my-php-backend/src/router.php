<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'controllers/RequestController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/StockController.php';
require_once 'helpers/jwt_helper.php';

function requireAuth($requiredRole = null)
{
    $headers = getallheaders();
    if (!isset($headers['Authorization']) || !preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Token lipsă sau invalid']);
        exit;
    }
    $jwt = $matches[1];
    $payload = validateJWT($jwt); // This should return the decoded payload if valid

    if (!$payload) {
        http_response_code(401);
        echo json_encode(['error' => 'Token invalid sau expirat']);
        exit;
    }

    // Check role if required
    if ($requiredRole && (!isset($payload['role']) || $payload['role'] !== $requiredRole)) {
        http_response_code(403);
        echo json_encode(['error' => 'Acces interzis']);
        exit;
    }

    return $payload; // return payload if you need user info later
}

function routeRequest($method, $uri)
{
    $uri = explode('?', $uri)[0];
    $segments = array_values(array_filter(explode('/', $uri)));

    if (in_array('requests', $segments)) {
        $requestController = new RequestController();

        // caută poziția lui 'requests' ca să afli ce urmează dupa
        $pos = array_search('requests', $segments);
        $action = $segments[$pos + 1] ?? null;

        switch ($method) {
            case 'POST':
                if ($action === 'approve') {
                    $id = $_POST['id'] ?? null;
                    if ($id) {
                        $requestController->approve($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "ID lipsă"]);
                    }
                } elseif ($action === 'reject') {
                    $id = $_POST['id'] ?? null;
                    if ($id) {
                        $requestController->reject($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "ID lipsă"]);
                    }
                } elseif ($action === 'delete') {
                    $id = $_POST['id'] ?? null;
                    if ($id) {
                        $requestController->delete($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "ID lipsă pentru stergere"]);
                    }
                } elseif ($action === 'reset-status') {
                    $id = $_POST['id'] ?? null;
                    if ($id) {
                        $requestController->resetStatus($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(["error" => "ID lipsă pentru resetare"]);
                    }
                } elseif ($action === 'respond') {
                    requireAuth('admin');
                    $requestController->respond();
                } else {
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
                }
                break;

            case 'GET':
                requireAuth('admin');
                $requestController->getRequests();
                break;

            default:
                sendError("Method not allowed.", 405);
        }
    } elseif (in_array('orders', $segments)) {
        requireAuth('admin');
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
    } elseif (in_array('piese', $segments)) {
        requireAuth('admin');
        $controller = new StockController();

        if ($method == 'GET') {
            $controller->getAll();
        } elseif ($method == 'POST' && in_array('update', $segments)) {
            $controller->update();
        } elseif ($method == 'POST') {
            $controller->add();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    } elseif (in_array('login', $segments)) {
        if ($method === 'POST') {
            require_once 'helpers/jwt_helper.php';

            if (isset($_POST["user"]) && isset($_POST["pass"])) {
                $pdo = new PDO("mysql:host=localhost;dbname=issuesdb", "root", "");
                $stmt = $pdo->prepare("SELECT id, password, email, role FROM users WHERE user_name = ?");
                $stmt->execute([$_POST["user"]]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data && $_POST['pass'] === $data['password']) {
                    $token = generateJWT([
                        'user_id' => $data['id'],
                        'user_name' => $_POST['user'],
                        'email' => $data['email'],
                        'role' => $data['role']
                    ]);
                    echo json_encode(['token' => $token]);
                } else {
                    sendError('Utilizator inexistent sau parolă greșită', 401);
                }
            } else {
                sendError('Date lipsă pentru autentificare', 400);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    } elseif (in_array('reviews', $segments)) {
        require_once 'controllers/ReviewController.php';
        $reviewController = new ReviewController();
        if ($method === 'POST') {
            $reviewController->add();
        } elseif ($method === 'GET') {
            $reviewController->getAll();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
    } else {
        sendError("Endpoint not found.", 404);
    }
}
