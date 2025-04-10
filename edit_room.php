<?php
require_once 'db.php';

// Проверка наличия ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Номер не найден.";
    exit;
}

$id = (int)$_GET['id'];
$message = '';

// Получение информации о номере
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    echo "Номер не найден.";
    exit;
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Подготовка и выполнение запроса на удаление
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Номер успешно удалён.";
            // Перенаправление на страницу списка номеров после удаления
            header("Location: rooms.php");
            exit;
        } else {
            $message = "Ошибка при удалении номера: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Обработка обновления данных
        $room_number = trim($_POST['room_number']);
        $type = trim($_POST['type']);
        $price = floatval($_POST['price']);
        $status = isset($_POST['status']) ? 1 : 0;

        if ($room_number && $type && $price > 0) {
            // Подготовка и выполнение запроса на обновление
            $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, type = ?, price = ?, status = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $room_number, $type, $price, $status, $id);

            if ($stmt->execute()) {
                $message = "Номер обновлён.";
                // Обновим переменную, чтобы при повторной загрузке форма показывала актуальные данные
                $room['room_number'] = $room_number;
                $room['type'] = $type;
                $room['price'] = $price;
                $room['status'] = $status;
            } else {
                $message = "Ошибка при обновлении номера: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Пожалуйста, заполните все поля корректно.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование номера</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Редактирование номера: <?php echo htmlspecialchars($room['room_number']); ?></h1>
    </header>

    <section class="form-container">
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post">
            <label>
                Номер:
                <input type="text" name="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
            </label><br><br>

            <label>
                Тип:
                <input type="text" name="type" value="<?php echo htmlspecialchars($room['type']); ?>" required>
            </label><br><br>

            <label>
                Цена:
                <input type="number" name="price" value="<?php echo htmlspecialchars($room['price']); ?>" min="0" step="0.01" required>
            </label><br><br>

            <label>
                <input type="checkbox" name="status" <?php echo $room['status'] ? 'checked' : ''; ?>>
                Доступен для бронирования
            </label><br><br>

            <button type="submit" name="update" class="submit-button">Сохранить изменения</button>
            <button type="submit" name="delete" onclick="return confirm('Вы уверены, что хотите удалить этот номер?');" class="delete-button">Удалить</button>
        </form>

        <p><a href="rooms.php" class="back-link">← Вернуться к списку номеров</a></p>
    </section>

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>