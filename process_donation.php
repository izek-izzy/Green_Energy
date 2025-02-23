<?php
include('config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();
    $user_id = $_SESSION['user_id'];
    $amount = $conn->real_escape_string($_POST['amount']);

    $sql = "INSERT INTO donations (user_id, amount) VALUES ('$user_id', '$amount')";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?success=1&amount=" . urlencode($amount));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
