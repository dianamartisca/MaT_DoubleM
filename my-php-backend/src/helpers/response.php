<?php
function sendResponse(int $statusCode, $message)
{
    http_response_code($statusCode);
    echo json_encode(['message' => $message]);
    exit;
}

function sendError($message, $status = 400)
{
    http_response_code($status);
    echo json_encode(['error' => $message]);
    exit;
}
