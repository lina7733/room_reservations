<?php
// Подключение к базе данных
require_once 'db.php';

// Проверка наличия параметра id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Номер не найден.";
    exit;
}

$id = (int)$_GET['id'];

// Получение информации о номере
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->bind_param("i", $id); // Привязываем параметр $id как целое число
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

if (!$room) {
    echo "Номер не найден.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($room['room_number']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Подключение внешнего CSS файла -->
</head>
<body>
    <header>
        <h1>Детали номера <?php echo htmlspecialchars($room['room_number']); ?></h1>
    </header>

    <section class="room-details">
        <p><strong>Описание:</strong> <?php echo nl2br(htmlspecialchars($room['type'])); ?></p>
        <p><strong>Цена:</strong> <?php echo number_format($room['price'], 2, ',', ' ') . ' руб.'; ?></p>
        <p><strong>Доступность:</strong> <?php echo $room['status'] == 'available' ? 'Доступен' : 'Занят'; ?></p>

        <?php if ($room['status'] == 'available'): ?>
            <a href="book_room.php?id=<?php echo $room['id']; ?>" class="button">Забронировать</a>
        <?php else: ?>
            <p>Этот номер недоступен для бронирования.</p>
        <?php endif; ?>

        <!-- Кнопка редактирования -->
        <a href="edit_room.php?id=<?php echo $room['id']; ?>" class="button">Редактировать номер</a>
        <p><a href="rooms.php">← Вернуться к списку номеров</a></p>
    </section>
    

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>