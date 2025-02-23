<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = connectDB();

// Delete associated records in the donations table
$sql_donations = "DELETE FROM donations WHERE user_id = ?";
$stmt_donations = $conn->prepare($sql_donations);
$stmt_donations->bind_param("i", $user_id);
$stmt_donations->execute();

// Delete user from the database
$sql_user = "DELETE FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);

if ($stmt_user->execute()) {
    // Unset session variables and destroy the session
    session_unset();
    session_destroy();
    
    // Redirect to home page
    header('Location: index.php?success=1&message=Profile+deleted');
} else {
    // Redirect back to edit profile with an error message
    header('Location: edit_profile.php?error=1&message=Failed+to+delete+profile');
}

$stmt_donations->close();
$stmt_user->close();
$conn->close();
?>
