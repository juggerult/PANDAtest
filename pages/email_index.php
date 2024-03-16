<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panda Test</title>
</head>
<body>
    <h2>Введите вашу почту:</h2>
    <form method="post" action="functions/send_verification_code.php">
        <input type="email" name="email" required>
        <button type="submit">Отправить код</button>
    </form>

    <?php if (isset($_SESSION["verification_code"])): ?>
        <h2>Введите код подтверждения:</h2>
        <form method="post" action="functions/verify_code.php">
            <input type="text" name="verification_code" required>
            <button type="submit">Подтвердить</button>
        </form>
    <?php endif; ?>
</body>
</html>
