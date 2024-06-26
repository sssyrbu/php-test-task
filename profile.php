<?php
session_start();
require 'utils.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user = getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "Passwords do not match.";
    } else {
        updateUser($_SESSION['user_id'], $name, $email, $phone, $password);
        $user = getUserById($_SESSION['user_id']);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h1>Profile</h1>
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= $user['phone'] ?>" required><br>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password"><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>