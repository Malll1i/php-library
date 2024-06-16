<?php
session_start();


if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "library";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];

    // Подготовка и выполнение SQL-запроса
    $stmt = $conn->prepare("INSERT INTO books (title, author, description, year, genre) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $author, $description, $year, $genre);

    if ($stmt->execute()) {
        echo "Книга успешно добавлена";
    } else {
        echo "Ошибка добавления книги: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
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
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff7e5f, #feb47b);
        }

        .admin-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .admin-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .admin-container input[type="text"],
        .admin-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .admin-container button {
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

        .admin-container button:hover {
            background: #feb47b;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Добавление книги</h2>
        <form action="admin.php" method="POST">
            <input type="text" name="title" placeholder="Наименование" required>
            <input type="text" name="author" placeholder="Автор" required>
            <textarea name="description" placeholder="Описание" required></textarea>
            <input type="text" name="year" placeholder="Год издания" required>
            <input type="text" name="genre" placeholder="Жанр" required>
            <button type="submit">Добавить книгу</button>
        </form>
    </div>
</body>
</html>
