<?php
// utils.php
require 'db_conn.php';

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