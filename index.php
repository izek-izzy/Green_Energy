<?php include('includes/header.php'); ?>

<main>
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['name'])) {
        echo '<div class="success-popup">Thank you for registering, ' . htmlspecialchars($_GET['name']) . '! <button id="closePopup">Close</button></div>';
    }
    ?>
    <section class="intro">
        <h1>Welcome to GreenFutures</h1>
        <p>Committed to accelerating the transition towards renewable energy sources to mitigate the impacts of climate change.</p>
    </section>
    
    <section class="carousel">
        <div class="carousel-container">
            <div class="carousel-slide">
                <img src="assets/images/slide1.jpg" alt="Slide 1" class="active">
                <img src="assets/images/slide2.jpg" alt="Slide 2">
                <img src="assets/images/slide3.jpg" alt="Slide 3">
                
            </div>
            <button class="carousel-btn prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-btn next" onclick="moveSlide(1)">&#10095;</button>
        </div>
    </section>
    
    <section class="latest-news">
        <h2>Latest News</h2>
        <ul>
            <?php
            include('config.php');
            $conn = connectDB();
            $sql = "SELECT title, description, link FROM news ORDER BY created_at DESC LIMIT 5";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li>';
                    echo '<a href="' . htmlspecialchars($row['link']) . '">';
                    echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '</a>';
                    echo '</li>';
                }
            } else {
                echo '<li>No news articles found.</li>';
            }
            $conn->close();
            ?>
        </ul>
    </section>
    
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div id="joinUsForm" class="join-us-popup">
        <h2 class="center-heading">Join Us!</h2>
        <form action="register.php" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">Register</button>
            <button type="button" id="notNowBtn">Not Now</button>
        </form>
    </div>
    <p>Already a member? <a href="login.php">Log in</a></p>
    <?php endif; ?>
</main>

<div id="cookieNotice" class="cookie-notice">
    <p>We use cookies to ensure you get the best experience on our website. By continuing to browse the site, you agree to our use of cookies.</p>
    <button id="acceptCookies">Accept</button>
    <button id="denyCookies">Deny</button>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const joinUsForm = document.getElementById('joinUsForm');
    const notNowBtn = document.getElementById('notNowBtn');
    const cookieNotice = document.getElementById('cookieNotice');
    const acceptCookies = document.getElementById('acceptCookies');
    const denyCookies = document.getElementById('denyCookies');

    // Function to set item in local storage
    function setLocalStorage(key, value) {
        localStorage.setItem(key, value);
    }

    // Function to get item from local storage
    function getLocalStorage(key) {
        return localStorage.getItem(key);
    }

    // Display the join us popup on page load
    if (joinUsForm) {
        setTimeout(() => {
            joinUsForm.style.display = 'block';
        }, 500); // Delay to ensure the popup is shown after page load
    }

    // Hide the join us popup when "Not Now" button is clicked
    notNowBtn.addEventListener('click', function() {
        joinUsForm.style.display = 'none';
        if (cookieNotice && !getLocalStorage('cookiesAccepted')) {
            cookieNotice.style.display = 'block';
        }
    });

    // Display cookie notice on page load if not already accepted
    if (cookieNotice && !getLocalStorage('cookiesAccepted')) {
        setTimeout(() => {
            cookieNotice.style.display = 'block';
        }, 1000); // Delay to ensure it shows up after the Join Us popup
    }

    // Hide cookie notice when "Accept" button is clicked
    acceptCookies.addEventListener('click', function() {
        setLocalStorage('cookiesAccepted', 'true');
        cookieNotice.style.display = 'none';
    });

    // Hide cookie notice when "Deny" button is clicked
    denyCookies.addEventListener('click', function() {
        setLocalStorage('cookiesAccepted', 'false');
        cookieNotice.style.display = 'none';
    });

    // Close success popup
    if (document.getElementById('successPopup')) {
        document.getElementById('closePopup').addEventListener('click', function() {
            document.getElementById('successPopup').style.display = 'none';
        });
    }
});
</script>
