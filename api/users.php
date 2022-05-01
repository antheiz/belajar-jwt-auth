<?php

require_once "config/Database.php";
require_once "init.php";

require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

// Mengeload dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

$headers = getallheaders();

// Periksa apakah header authorization-nya ada
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    exit();
}

try {

    if ($_COOKIE['X-SESSION']) {

        $token = $_COOKIE['X-SESSION'];

        // Men-decode token. Dalam library ini juga sudah sekaligus memverfikasinya
        $payload = JWT::decode($token, new Key($_ENV['ACCESS_TOKEN_SECRET'], 'HS256'));
        // var_dump($payload);        
        $user_data = $user->get_data($payload->{ 'username'});
        unset($user_data['password']);
        echo json_encode([
            'success' => true,
            "user" => $user_data,

        ]);

    }
    else {
        echo json_encode([
            'success' => false,
            'data' => null,
            'message' => 'Data gagal diload'
        ]);
        exit();
    }

}
catch (Exception $e) {
    // Bagian ini akan jalan jika terdapat error saat JWT diverifikasi atau di-decode
    http_response_code(401);
    exit();
}