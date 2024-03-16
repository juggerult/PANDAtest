<?php
session_start();

require_once '../../database.php';

if (!isset($_SESSION["verification_code"]) || !isset($_SESSION["email"])) {
    header("Location: ../email_index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verification_code"])) {
    if ($_POST["verification_code"] == $_SESSION["verification_code"]) {
        $update_sql = "UPDATE subscriptions SET email_verify = true WHERE email = ? AND email_verify = false";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$_SESSION["email"]]);        
    } else {
        echo "Введен неверный код подтверждения.";
    }
}


?>
