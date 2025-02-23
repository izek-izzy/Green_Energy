<?php
include('config.php');

$errors = [];
$first_name = $last_name = $email = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php?success=1&name=" . urlencode($first_name));
            exit();
        } else {
            $errors[] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<?php include('includes/header.php'); ?>
<main>
    <h1>Register</h1>
    <?php
    if (!empty($errors)) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
    ?>
    <form action="register.php" method="post" id="registerForm">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <button type="submit">Register</button>
    </form>
    <p>Already a member? <a href="login.php">Login here</a></p>
</main>
<?php include('includes/footer.php'); ?>

<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    let valid = true;

    if (password.value !== confirmPassword.value) {
        password.classList.add('error');
        confirmPassword.classList.add('error');
        valid = false;
    } else {
        password.classList.remove('error');
        confirmPassword.classList.remove('error');
        password.classList.add('valid');
        confirmPassword.classList.add('valid');
    }

    if (!valid) {
        event.preventDefault();
    }
});
</script>
