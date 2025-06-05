<?php
require_once '../src/config/database.php';
require_once '../src/helpers/response.php';
require_once '../src/router.php';


$method = $_SERVER['REQUEST_METHOD'];
$requestUri = trim($_SERVER['REQUEST_URI'], '/');

try {
    routeRequest($method, $requestUri);
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}
