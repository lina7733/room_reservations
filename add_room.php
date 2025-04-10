<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'hotel_booking';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы и защита от SQL-инъекций
    $room_number = $conn->real_escape_string($_POST['room_number']);
    $type = $conn->real_escape_string($_POST['type']);
    $price = (float) $_POST['price'];
    $status = $conn->real_escape_string($_POST['status']);

    // Подготовка SQL-запроса
    $stmt = $conn->prepare("INSERT INTO rooms (room_number, type, price, status) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    // Привязка параметров
    $stmt->bind_param("ssds", $room_number, $type, $price, $status);

    // Выполнение запроса
    if ($stmt->execute()) {
        $message = "Номер успешно добавлен!";
    } else {
        $message = "Ошибка при добавлении номера: " . $stmt->error;
    }

    // Закрытие подготовленного выражения
    $stmt->close();
}

// Закрытие соединения
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить номер</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Подключение внешнего CSS файла -->
</head>
<body>
    <header>
        <h1>Добавление нового номера</h1>
    </header>

    <section class="form-container">
        <?php if (isset($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post" action="add_room.php">
            <label for="room_number">Номер комнаты:</label><br>
            <input type="text" id="room_number" name="room_number" required><br><br>

            <label for="type">Тип:</label><br>
            <input type="text" id="type" name="type" required><br><br>

            <label for="price">Цена (в рублях):</label><br>
            <input type="number" id="price" name="price" step="0.01" required><br><br>

            <label for="status">Статус:</label><br>
            <input type="text" id="status" name="status" required><br><br>

            <button type="submit" class="submit-button">Добавить</button>
        </form>

        <p><a href="rooms.php" class="back-link">← Вернуться к списку номеров</a></p>
    </section>

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>