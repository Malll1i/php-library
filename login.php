<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Проверка учетных данных администратора
    if ($user === 'admin' && $pass === '123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        // Проверка учетных данных обычных пользователей
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['user_logged_in'] = true;
            header('Location: books.php');
            exit();
        } else {
            echo "Неудача";
        }

        $stmt->close();
    }
}

$conn->close();
?>
