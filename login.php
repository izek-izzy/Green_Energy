<?php
include('config.php');

session_start();

$errors = [];
$email = '';
$attempts_left = null;
$lockout_time_remaining = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT id, password, failed_attempts, lockout_time FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_time = date("Y-m-d H:i:s");
        
        if ($row['lockout_time'] && strtotime($current_time) < strtotime($row['lockout_time'])) {
            $lockout_time_remaining = strtotime($row['lockout_time']) - strtotime($current_time);
            $errors[] = "Your account is locked. Please try again later.";
        } else {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $sql = "UPDATE users SET failed_attempts = 0, lockout_time = NULL WHERE id=" . $row['id'];
                $conn->query($sql);
                header("Location: index.php");
                exit();
            } else {
                $failed_attempts = $row['failed_attempts'] + 1;
                $attempts_left = 3 - $failed_attempts;
                if ($failed_attempts >= 3) {
                    $lockout_time = date("Y-m-d H:i:s", strtotime("+3 minutes"));
                    $sql = "UPDATE users SET failed_attempts = $failed_attempts, lockout_time = '$lockout_time' WHERE id=" . $row['id'];
                    $errors[] = "Your account is locked. Please try again later.";
                } else {
                    $sql = "UPDATE users SET failed_attempts = $failed_attempts WHERE id=" . $row['id'];
                    $errors[] = "Invalid password. You have $attempts_left attempt(s) left.";
                }
                $conn->query($sql);
            }
        }
    } else {
        $errors[] = "No user found with that email.";
    }

    $conn->close();
}
?>

<?php include('includes/header.php'); ?>
<main class="form-container">
    <div class="form">
        <h1>Login</h1>
        <?php
        if (!empty($errors)) {
            echo '<div class="error-messages">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        if ($lockout_time_remaining) {
            echo '<div id="countdown">Your account is locked. Please wait for <span id="countdown-timer"></span> to try again.</div>';
        }
        ?>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Not yet a member? <a href="register.php">Register here</a></p>
    </div>
</main>
<?php include('includes/footer.php'); ?>

<?php if ($lockout_time_remaining): ?>
<script>
    let lockoutTimeRemaining = <?php echo $lockout_time_remaining; ?>;

    function startCountdown() {
        const countdownElement = document.getElementById('countdown-timer');
        if (lockoutTimeRemaining > 0) {
            const minutes = Math.floor(lockoutTimeRemaining / 60);
            const seconds = lockoutTimeRemaining % 60;
            countdownElement.innerHTML = `${minutes} minute(s) and ${seconds} second(s)`;
            lockoutTimeRemaining--;
            setTimeout(startCountdown, 1000);
        } else {
            countdownElement.innerHTML = 'You can now try again.';
        }
    }

    startCountdown();
</script>
<?php endif; ?>
