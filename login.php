<?php
// login.php
require 'utils.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    if (isset($_POST['smart-token'])) {
        $captchaResponse = $_POST['smart-token'];
    } else {
        echo "Captcha token is missing.<br>";
        exit();
    }

    if (!check_captcha($captchaResponse)) {
        echo "Captcha validation failed.";
        exit();
    }

    if (loginUser($email_or_phone, $password)) {
        header('Location: profile.php');
        exit();
    } else {
        echo "Invalid email or phone, or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</head>
<body>
    <h1>Login</h1>
    <form method="post">
        <label for="email_or_phone">Email or Phone:</label>
        <input type="text" id="email_or_phone" name="email_or_phone" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <div
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="ysc1_Zdt9xwsbRBdSPRTKx79yGtN01M5z5WnLcxHGhLyt5d9b07dc"
            style="height: 100px"
        ></div>
        <input type="submit" value="Login">
    </form>
    <script>
        window.smartCaptcha.render('captcha-container', {
            sitekey: 'ysc1_Zdt9xwsbRBdSPRTKx79yGtN01M5z5WnLcxHGhLyt5d9b07dc',
            callback: function() {
                console.log('Captcha widget rendered successfully');
            }
        });
    </script>
</body>
</html>