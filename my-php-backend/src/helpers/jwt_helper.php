<?php
require_once __DIR__ . '/../../vendor/JWT.php';
require_once __DIR__ . '/../../vendor/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const JWT_SECRET = 'doubleMsecretkey';

function generateJWT($payload, $expMinutes = 60)
{
    $issuedAt = time();
    $expire = $issuedAt + ($expMinutes * 60);
    $payload['iat'] = $issuedAt;
    $payload['exp'] = $expire;
    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function validateJWT($jwt)
{
    try {
        $decoded = JWT::decode($jwt, new Key(JWT_SECRET, 'HS256'));
        return (array)$decoded;
    } catch (Exception $e) {
        return false;
    }
}
