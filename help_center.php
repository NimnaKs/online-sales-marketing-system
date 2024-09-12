<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Shop Master</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/help_center.css">
</head>

<body>

    <?php include('header.php'); ?>

    <section class="help-center">
        
        <div class="help-header">
            <div class="help-header-content">
                <h1>Help Center</h1>
                <p>Find answers to frequently asked questions or contact our support team for assistance.</p>
                <a href="#contact_section" class="help-button">Contact Support</a>
            </div>
         </div>


         <div class="help-content">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-section">
                <div class="faq-card">
                    <h3>How do I create an account?</h3>
                    <p>To create an account, click the 'Register' button at the top of the page, fill in your details, and submit the form.</p>
                </div>
                <div class="faq-card">
                    <h3>What payment methods do you accept?</h3>
                    <p>We accept a wide range of payment methods, including credit/debit cards, PayPal, and more.</p>
                </div>
                <div class="faq-card">
                    <h3>How can I track my order?</h3>
                    <p>Once your order is shipped, you will receive a tracking link via email to monitor your delivery progress.</p>
                </div>
                <div class="faq-card">
                    <h3>Can I return a product?</h3>
                    <p>Yes, we offer a 30-day return policy. Please visit our <a href="return-policy.php">Return Policy</a> page for more details.</p>
                </div>
            </div>
        </div>

        <div id="contact_section" class="contact-section">
            <div class="contact-overlay"></div>
            <div class="contact-content">
                <h2>Still Need Help?</h2>
                <p>If you didn't find what you were looking for, please contact our support team or provide your details below:</p>
                <form action="submit_feedback.php" method="post">
                    <input type="email" name="email" placeholder="Your Email" required class="contact-input">
                    <textarea name="description" placeholder="Describe your issue" required class="contact-textarea"></textarea>
                    <button type="submit" class="cta-button">Submit Feedback</button>
                </form>
            </div>
        </div>

    </section>

    <!-- Include Footer -->
    <?php include('footer.php'); ?>

    <script src="assets/js/script.js"></script>
</body>
</html>
