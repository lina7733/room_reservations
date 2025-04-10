<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Задаем логин и пароль вручную
    $admin_login = 'admin';
    $admin_password = '12345';

    if ($login === $admin_login && $password === $admin_password) {
        $_SESSION['admin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход администратора</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Подключение внешнего CSS файла -->
</head>
<body>
    <header>
        <h1>Вход администратора</h1>
    </header>

    <section>
        <div class="login-container">
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="post" class="login-form">
                <label for="login">Логин:</label>
                <input type="text" name="login" id="login" required>
                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password" required>
                <input type="submit" value="Войти">
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>