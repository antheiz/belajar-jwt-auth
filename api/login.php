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

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// Ambil input JSON
$data = json_decode(file_get_contents("php://input"));

// Masukan data dari Input JSON ke Variable
$username = $data->username;
$password = $data->password;


// Mengecek apakah username sudah terdaftar atau belum
if ($user->check_name($username)) {

    // Mengecek username dan password apakah sesuai atau tidak
    if ($user->login_user($username, $password)) {

        // Menghitung waktu kadaluarsa token. Dalam kasus ini akan terjadi setelah 15 menit
        $expired_time = time() + (15 * 60);

        // Buat payload dan access token
        $payload = [
            'username' => $username,
            // Di library ini wajib menambah key exp untuk mengatur masa berlaku token
            'exp' => $expired_time
        ];

        // Men-generate access token
        $access_token = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET'], 'HS256');

        // Kirim kembali ke user
        echo json_encode([
            'success' => true,
            'data' => [
                'accessToken' => $access_token,
                'expiry' => date(DATE_RFC850, $expired_time)
            ],
            'message' => 'Login berhasil!'
        ]);

        setcookie('X-SESSION', $access_token, $payload['exp'], '', '', false, true);

    }
    else {
        // Atur jenis response
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'data' => null,
            'message' => 'Username atau password tidak sesuai'
        ]);
        exit();
    }

}
else {
    // Atur jenis response
    header('Content-Type: application/json');

    echo json_encode([
        'success' => false,
        'data' => null,
        'message' => 'Username belum terdaftar'
    ]);
    exit();
}