<?php
require_once 'db.php'; // Подключение к базе данных

// Получаем все доступные номера из базы данных
$query = "SELECT * FROM rooms WHERE status = 'available' ORDER BY price ASC"; // Сортировка по цене (возрастание)
$result = $conn->query($query);

// Проверка на успешность выполнения запроса
if ($result === false) {
    die('Ошибка выполнения запроса: ' . $conn->error);
}

if ($result->num_rows > 0) {
    // Извлекаем все строки результата в ассоциативный массив
    $rooms = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $rooms = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список номеров</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Подключение внешнего CSS файла -->
</head>
<body>
    <header>
        <h1>Наши номера</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="add_room.php">Добавить номер</a></li>
                <li><a href="logout.php">Выйти</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Доступные номера</h2>

        <?php if (!empty($rooms)): ?>
            <div class="rooms-container">
                <?php foreach ($rooms as $room): ?>
                    <div class="room">
                        <h3><?php echo htmlspecialchars($room['room_number']); ?></h3>
                        <p><?php echo htmlspecialchars($room['type']); ?></p>
                        <p>Цена: <?php echo number_format($room['price'], 2, ',', ' ') . " руб."; ?></p>
                        <a href="view_room.php?id=<?php echo $room['id']; ?>">Подробнее</a>
                        <a href="booking_room.php?id=<?php echo $room['id']; ?>">Забронировать</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Нет доступных номеров.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Гостиница XYZ</p>
    </footer>
</body>
</html>