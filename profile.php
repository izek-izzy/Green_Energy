<?php
include('config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = connectDB();
$user_id = $_SESSION['user_id'];

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $contact_details = $conn->real_escape_string($_POST['contact_details']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
    }

    $sql = "UPDATE users SET contact_details='$contact_details', address='$address'";
    if ($profile_picture) {
        $sql .= ", profile_picture='$profile_picture'";
    }
    $sql .= " WHERE id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php?success=1");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$sql = "SELECT first_name, last_name, email, contact_details, address, profile_picture FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$sql = "SELECT amount, created_at FROM donations WHERE user_id='$user_id' ORDER BY created_at DESC";
$result = $conn->query($sql);
$donations = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
}
$conn->close();
?>

<?php include('includes/header.php'); ?>
<main class="profile-container">
    <div class="profile">
        <h1>Personal Profile</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-popup" id="successPopup">Your profile has been updated successfully!</div>
        <?php endif; ?>
        
        <!-- View Profile Section -->
        <div id="viewProfile">
            <div class="profile-picture">
                <?php if ($user['profile_picture']): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="assets/images/default-profile.png" alt="Default Profile Picture">
                <?php endif; ?>
            </div>
            <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Contact Details:</strong> <?php echo htmlspecialchars($user['contact_details']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <button type="button" id="editProfileBtn">Edit Profile</button>
        </div>
        
        <!-- Edit Profile Section -->
        <div id="editProfile" style="display: none;">
            <form action="profile.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture">
                    <?php if ($user['profile_picture']): ?>
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                    <?php else: ?>
                        <img src="assets/images/default-profile.png" alt="Default Profile Picture">
                    <?php endif; ?>
                    <input type="file" name="profile_picture">
                </div>
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" disabled>
                
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" disabled>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                
                <label for="contact_details">Contact Details:</label>
                <input type="text" id="contact_details" name="contact_details" value="<?php echo htmlspecialchars($user['contact_details']); ?>">
                
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                
                <button type="submit" name="update_profile">Update Profile</button>
                <button type="button" id="deleteProfileBtn">Delete Profile</button>
                <button type="button" id="cancelEditBtn">Cancel</button>
            </form>
        </div>
        
        <h2>Your Donation History</h2>
        <table>
            <thead>
                <tr>
                    <th>Amount (USD)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($donation['amount']); ?></td>
                        <td><?php echo htmlspecialchars($donation['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('click', function() {
    const successPopup = document.getElementById('successPopup');
    if (successPopup) {
        successPopup.style.display = 'none';
    }
});

document.getElementById('editProfileBtn').addEventListener('click', function() {
    document.getElementById('viewProfile').style.display = 'none';
    document.getElementById('editProfile').style.display = 'block';
});

document.getElementById('cancelEditBtn').addEventListener('click', function() {
    document.getElementById('editProfile').style.display = 'none';
    document.getElementById('viewProfile').style.display = 'block';
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteProfileBtn = document.getElementById('deleteProfileBtn');

    deleteProfileBtn.addEventListener('click', function() {
        if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
            window.location.href = 'delete_profile.php';
        }
    });
});

</script>
