<?php include('includes/header.php'); ?>

<main class="form-container">
    <div class="form">
        <h1>Contact Us</h1>
        <div class="contact-details">
            <p><strong>Phone:</strong> +1 (123) 456-7890</p>
            <p><strong>Email:</strong> <a href="mailto:info@greenfutures.com">info@greenfutures.com</a></p>
            <p><strong>Address:</strong> 123 Green Street, Renewable City, Earth</p>
        </div>
        <form action="process_contact.php" method="post">
            <?php
            $name = '';
            $email = '';
            if (isset($_SESSION['user_id'])) {
                include('config.php');
                $conn = connectDB();
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT first_name, last_name, email FROM users WHERE id='$user_id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $name = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
                    $email = htmlspecialchars($user['email']);
                }
                $conn->close();
            }
            ?>
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" <?php if ($name) echo 'readonly'; ?> required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" <?php if ($email) echo 'readonly'; ?> required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="6" required></textarea>
            
            <button type="submit">Send Message</button>
        </form>
    </div>
</main>

<?php include('includes/footer.php'); ?>
