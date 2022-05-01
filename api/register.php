<?php

require_once "config/Database.php";
require_once "init.php";

require_once "../vendor/autoload.php";

use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Mengeload dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// Ambil input JSON
$data = json_decode(file_get_contents("php://input"));

// Masukan data dari Input JSON ke Variable
$username = $data->username;
$password = $data->password;

$hash = password_hash($password, PASSWORD_BCRYPT);

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode(array("message" => "Request method tidak di izinkan"));
    exit();


}
else {
    $user->register_user(array(
        'username' => $username,
        'password' => $hash
    ));

    echo json_encode(array("message" => "User berhasil ditambahkan."));
}
