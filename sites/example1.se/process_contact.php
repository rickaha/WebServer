<?php
// Start session to access the token
session_start();

// Check for CSRF token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
    die('CSRF validation failed. Please try again.');
}
// Load PHPMailer
require '/var/www/vendor/vendor/autoload.php';

// Load env variables
$config = include('/var/www/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW));
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW));

    // Validate inputs
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no errors, send email
    if (empty($errors)) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8'; // For Scandinavian letters

        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                // Enable verbose debug output (for testing)
            $mail->isSMTP();                                        // Send using SMTP
            $mail->Host       = $config['smtp_server'];             // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                               // Enable SMTP authentication
            $mail->Username   = $config['smtp_username'];           // SMTP username
            $mail->Password   = $config['smtp_password'];           // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        // Enable TLS encryption
            $mail->Port       = 465;                                // TCP port to connect to (use 465 for `PHPMailer::ENCRYPTION_SMTPS`)

            // Recipients
            $mail->setFrom($config['smtp_username'], 'Contact Form');
            $mail->addAddress($config['mail_recipient']);           // Add a recipient
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Contact Form Submission from $name";

            // Create HTML message
            $email_body_html = "
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
            ";

            // Create plain text message
            $email_body_text = "
                New Contact Form Submission

                Name: {$name}
                Email: {$email}
                Message:
                {$message}
            ";

            $mail->Body = $email_body_html;
            $mail->AltBody = $email_body_text;

            $mail->send();
            $success_message = "Thank you! Your message has been sent.";

            // Redirect option (uncomment to use)
            // header("Location: thank_you.html");
            // exit();

        } catch (Exception $e) {
            $errors[] = "Sorry, there was an error sending your message: " . $mail->ErrorInfo;
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Contact Form Result</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!-- Add your CSS links here -->
</head>
<body>
    <div id="wrapper">
        <div id="main">
            <article id="contact">
                <h2 class="major">Contact</h2>

                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p><a href="javascript:history.back()">Go back</a> and try again.</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="success-message">
                        <p><?php echo htmlspecialchars($success_message); ?></p>
                        <p><a href="/">Return to homepage</a></p>
                    </div>
                <?php endif; ?>
            </article>
        </div>
    </div>
</body>
</html>
