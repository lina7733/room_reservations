<?php
require_once 'db.php';

// Получение списка доступных номеров
$result = $conn->query("SELECT * FROM rooms WHERE status = 'available'");
$rooms = $result->fetch_all(MYSQLI_ASSOC);

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Вставка данных о госте
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $room_id = (int)$_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if ($name && $email && $room_id && $check_in && $check_out) {
        // Вставка гостя
        $stmt = $conn->prepare("INSERT INTO guests (name, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $phone);
        $stmt->execute();
        $guest_id = $stmt->insert_id;
        $stmt->close();

        // Вставка бронирования
        $stmt = $conn->prepare("INSERT INTO bookings (room_id, guest_id, check_in, check_out) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $room_id, $guest_id, $check_in, $check_out);
        $stmt->execute();
        $stmt->close();

        // Обновление статуса номера на 'booked'
        $stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $stmt->close();

        $message = "Бронирование успешно создано!";
    } else {
        $message = "Пожалуйста, заполните все обязательные поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Бронирование номера</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Подключение внешнего CSS файла -->
</head>
<body>
    <header>
        <h1>Форма бронирования</h1>
    </header>

    <section class="form-container">
        <?php if (isset($message)) : ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post">
            <label>
                Имя:
                <input type="text" name="name" required>
            </label><br><br>

            <label>
                Email:
                <input type="email" name="email" required>
            </label><br><br>

            <label>
                Телефон:
                <input type="text" name="phone">
            </label><br><br>

            <label>
                Номер:
                <select name="room_id" required>
                    <option value="">Выберите номер</option>
                    <?php foreach ($rooms as $room) : ?>
                        <option value="<?php echo $room['id']; ?>">
                            <?php echo htmlspecialchars($room['room_number']); ?> - <?php echo htmlspecialchars($room['type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label><br><br>

            <label>
                Дата заезда:
                <input type="date" name="check_in" required>
            </label><br><br>

            <label>
                Дата выезда:
                <input type="date" name="check_out" required>
            </label><br><br>

            <button type="submit" class="submit-button">Забронировать</button>
        </form>

        <p><a href="rooms.php" class="back-link">← Вернуться к списку номеров</a></p>
    </section>

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>