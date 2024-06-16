<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
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

// Получение данных из формы фильтров
$title_filter = isset($_GET['title']) ? $_GET['title'] : '';
$author_filter = isset($_GET['author']) ? $_GET['author'] : '';
$year_filter = isset($_GET['year']) ? $_GET['year'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

// Построение SQL-запроса с учетом фильтров
$sql = "SELECT * FROM books WHERE 1=1";

if (!empty($title_filter)) {
    $sql .= " AND title LIKE '%$title_filter%'";
}
if (!empty($author_filter)) {
    $sql .= " AND author LIKE '%$author_filter%'";
}
if (!empty($year_filter)) {
    $sql .= " AND year = '$year_filter'";
}
if (!empty($genre_filter)) {
    $sql .= " AND genre LIKE '%$genre_filter%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Библиотека</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
        }

        .filter-container, .books-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px 0;
        }

        .filter-container h2, .books-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .filter-container form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .filter-container input[type="text"] {
            width: 48%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .filter-container button {
            width: 100%;
            padding: 10px;
            background: #ff7e5f;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .filter-container button:hover {
            background: #feb47b;
        }

        .book {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .book:last-child {
            border-bottom: none;
        }

        .book-title {
            font-size: 18px;
            font-weight: bold;
        }

        .book-author {
            font-size: 16px;
            color: #666;
        }

        .book-description {
            margin-top: 10px;
            font-size: 14px;
        }

        .book-year, .book-genre {
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="filter-container">
        <h2>Фильтры</h2>
        <form action="books.php" method="GET">
            <input type="text" name="title" placeholder="Наименование" value="<?php echo htmlspecialchars($title_filter); ?>">
            <input type="text" name="author" placeholder="Автор" value="<?php echo htmlspecialchars($author_filter); ?>">
            <input type="text" name="year" placeholder="Год издания" value="<?php echo htmlspecialchars($year_filter); ?>">
            <input type="text" name="genre" placeholder="Жанр" value="<?php echo htmlspecialchars($genre_filter); ?>">
            <button type="submit">Применить фильтры</button>
        </form>
    </div>

    <div class="books-container">
        <h2>Список книг</h2>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='book'>";
                echo "<div class='book-title'>" . htmlspecialchars($row["title"]) . "</div>";
                echo "<div class='book-author'>Автор: " . htmlspecialchars($row["author"]) . "</div>";
                echo "<div class='book-description'>" . htmlspecialchars($row["description"]) . "</div>";
                echo "<div class='book-year'>Год издания: " . htmlspecialchars($row["year"]) . "</div>";
                echo "<div class='book-genre'>Жанр: " . htmlspecialchars($row["genre"]) . "</div>";
                echo "</div>";
            }
        } else {
            echo "Нет доступных книг.";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
