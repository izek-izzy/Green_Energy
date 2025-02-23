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
<main class="form-container">
    <div class="form">
        <h1>Dashboard</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['amount'])): ?>
            <div class="success-popup" id="successPopup">
                Thank you for your donation of $<?php echo htmlspecialchars($_GET['amount']); ?>! Your contribution helps us further our mission to promote renewable energy and combat climate change. We greatly appreciate your support.
            </div>
        <?php endif; ?>
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
</script>
