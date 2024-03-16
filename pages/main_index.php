<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>Panda Test</title>
</head>
<body>
    <h2>Введите email и ссылку:</h2>
    <form method="post" action="functions/send_new_subscriptions.php">
            Email: <input type="email" name="email" id='email'><br>
            Ссылка: <input type="text" name="link" id='link'><br>
        <button type="submit">Подписаться</button>
    </form>
</body>
</html>
