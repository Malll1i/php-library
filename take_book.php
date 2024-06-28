<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "library";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $book_id = $_POST['book_id'];
    $phone_number = $_POST['phone_number'];
    $username = $_SESSION['username'];
    $borrow_date = date('Y-m-d'); // Текущая дата

    $stmt = $conn->prepare("INSERT INTO book_borrowers (book_id, username, phone_number, borrow_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $book_id, $username, $phone_number, $borrow_date);

    if ($stmt->execute()) {
        echo "Книга успешно взята на чтение";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: books.php');
    exit();
}
?>
