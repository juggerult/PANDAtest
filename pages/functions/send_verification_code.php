<?php
session_start();

require_once '../../database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
    $code = rand(100000, 999999);
    $to = $_POST["email"];
    $subject = "Код подтверждения";
    $message = "Ваш код подтверждения: $code";
    $headers = "From: koval@pandatestkoval.zzz.com.ua";

    if (mail($to, $subject, $message, $headers)) {
        $_SESSION["verification_code"] = $code;
        $_SESSION["email"] = $to;
        header("Location: ../email_index.php");
        exit;
    } else {
        echo "Ошибка при отправке кода подтверждения.";
    }
}


?>
