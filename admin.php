<?php
session_start();

// Проверка, авторизован ли пользователь и является ли он админом
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in'] || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Настройки базы данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение запросов на взятие книг
$sql = "SELECT book_requests.id, books.title, book_requests.user, book_requests.phone_number, book_requests.request_date
        FROM book_requests
        JOIN books ON book_requests.book_id = books.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
        }

        .admin-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px 0;
        }

        .admin-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .request {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .request:last-child {
            border-bottom: none;
        }

        .request-title, .request-user, .request-phone, .request-date {
            font-size: 14px;
            color: #666;
        }

        .request-title {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Запросы на взятие книг</h2>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='request'>";
                echo "<div class='request-title'>Книга: " . htmlspecialchars($row["title"]) . "</div>";
                echo "<div class='request-user'>Пользователь: " . htmlspecialchars($row["user"]) . "</div>";
                echo "<div class='request-phone'>Телефон: " . htmlspecialchars($row["phone_number"]) . "</div>";
                echo "<div class='request-date'>Дата запроса: " . htmlspecialchars($row["request_date"]) . "</div>";
                echo "</div>";
            }
        } else {
            echo "Нет запросов на взятие книг.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
