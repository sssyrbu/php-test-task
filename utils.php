<?php
// utils.php
require 'db_conn.php';

define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_Zdt9xwsbRBdSPRTKx79yxIz4eJNAuo8aEQvNOK6Ke22cfd28');

function check_captcha($token) {
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'], // You need to provide the user IP.
                                         // Method for retrieving the user IP depends on your proxy.
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}


function isUserExists($email, $phone) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email, $phone]);
    return $stmt->fetch();
}

function registerUser($name, $email, $phone, $password) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $hashedPassword]);
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateUser($id, $name, $email, $phone, $password = null) {
    global $pdo;
    $sql = "UPDATE users SET name = ?, email = ?, phone = ?";
    $params = [$name, $email, $phone];
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password = ?";
        $params[] = $hashedPassword;
    }
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}
?>