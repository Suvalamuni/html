<?php
// contact-process.php
// Simple, minimal PHP mail handler with basic sanitization.
// NOTE: On many hosts mail() works; on local machine you might need to configure sendmail/SMTP.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

// Helper: sanitize input
function clean($s) {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

$name    = isset($_POST['name']) ? clean($_POST['name']) : '';
$email   = isset($_POST['email']) ? clean($_POST['email']) : '';
$phone   = isset($_POST['phone']) ? clean($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? clean($_POST['subject']) : 'Contact from website';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo 'Please fill required fields (name, email, message).';
    exit;
}

// Basic email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo 'Invalid email address.';
    exit;
}

$owner_email = 'info@uniquehire.co.in'; // change if needed
$to = $owner_email;
$subject_line = "Website contact: " . $subject;
$body = "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n\n";
$body .= "Message:\n$message\n";

// Headers
$headers = "From: \"$name\" <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Try to send
$sent = false;
if (function_exists('mail')) {
    $sent = mail($to, $subject_line, $body, $headers);
}

if ($sent) {
    // Redirect back or show a message
    header('Location: thank-you.html');
    exit;
} else {
    http_response_code(500);
    echo 'Mail could not be sent on this server. Contact server admin or configure SMTP.';
    exit;
}
?>
