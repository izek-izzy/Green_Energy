<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include('includes/header.php'); ?>
<main class="form-container">
    <div class="form">
        <h1>Donations</h1>
        <div class="donation-message">
            <p>You Can Make a Difference Right Now</p>
            <p>Your contributions make a significant impact. Every donation helps us further our mission to promote renewable energy and combat climate change. Thank you for your support! Donations are 100% tax-deductible. Less than 3% of your gift goes to administrative costs.</p>
        </div>
        <form action="process_donation.php" method="post">
            <label for="amount">Donation Amount (in USD):</label>
            <div class="preset-amounts">
                <button type="button" class="preset-amount" data-amount="700">$700</button>
                <button type="button" class="preset-amount" data-amount="400">$400</button>
                <button type="button" class="preset-amount" data-amount="300">$300</button>
                <button type="button" class="preset-amount" data-amount="200">$200</button>
                <button type="button" class="preset-amount" data-amount="140">$140</button>
                <button type="button" class="preset-amount" data-amount="100">$100</button>
            </div>
            <label for="custom-amount">Or enter a custom amount:</label>
            <input type="number" id="custom-amount" name="amount" min="1" step="0.01" required>
            
            <button type="submit">Donate</button>
        </form>
    </div>
</main>
<?php include('includes/footer.php'); ?>

<script>
document.querySelectorAll('.preset-amount').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('custom-amount').value = this.getAttribute('data-amount');
    });
});
</script>
