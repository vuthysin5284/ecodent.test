<?php
session_start();
$log = $_SESSION['uid'];
if (!isset($log)) { header('Location: login.php'); }
include_once '../../../inc/config.php';


// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$feedback_type = isset($_POST['feedback_type']) ? trim($_POST['feedback_type']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate inputs
$errors = array();

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($feedback_type)) {
    $errors[] = 'Feedback type is required';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If validation fails, redirect back with error
if (!empty($errors)) {
    $_SESSION['feedback_error'] = implode('<br>', $errors);
    header('Location: feedback.php');
    exit;
}

// Prepare email
$to = 'justinmcgrath168@gmail.com'; // Your support email
$email_subject = "[Dental System Feedback] {$subject}";

// Get user info from database for additional context
$user_info_query = "SELECT `staff_fname`, `staff_position_id` FROM `tbl_staff` WHERE `id` = ?";
$stmt = $CON->prepare($user_info_query);
$stmt->bind_param("i", $log);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$username = isset($user_data['staff_fname']) ? $user_data['staff_fname'] : 'Unknown';
$role = isset($user_data['staff_position_id']) ? $user_data['staff_position_id'] : 'Unknown';

// Build email body
$body = "
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
    .container { max-width: 600px; margin: 0 auto; }
    .header { background: #4F46E5; color: #fff; padding: 10px 20px; }
    .content { padding: 20px; background: #f9f9f9; border: 1px solid #ddd; }
    .footer { font-size: 12px; color: #777; padding: 10px 20px; text-align: center; }
    .info-item { margin-bottom: 10px; }
    .label { font-weight: bold; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='header'>
      <h2>New Feedback Submission</h2>
    </div>
    <div class='content'>
      <div class='info-item'>
        <span class='label'>Type:</span> {$feedback_type}
      </div>
      <div class='info-item'>
        <span class='label'>From:</span> {$name} ({$email})
      </div>
      <div class='info-item'>
        <span class='label'>User:</span> {$username} (Role: {$role}, ID: {$log})
      </div>
      <div class='info-item'>
        <span class='label'>Subject:</span> {$subject}
      </div>
      <div class='info-item'>
        <span class='label'>Message:</span>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
      </div>
      <div class='info-item'>
        <span class='label'>Date:</span> " . date('Y-m-d H:i:s') . "
      </div>
      <div class='info-item'>
        <span class='label'>IP Address:</span> " . $_SERVER['REMOTE_ADDR'] . "
      </div>
    </div>
    <div class='footer'>
      This is an automated email from your Dental Management System.
    </div>
  </div>
</body>
</html>
";

// Email headers
$headers = array();
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=UTF-8';
$headers[] = 'From: ' . $email;
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'X-Mailer: PHP/' . phpversion();

// Try to send email
try {
    // Log feedback in database
    $insert_query = "INSERT INTO feedback (user_id, name, email, feedback_type, subject, message, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $CON->prepare($insert_query);
    $stmt->bind_param("isssss", $log, $name, $email, $feedback_type, $subject, $message);
    $stmt->execute();
    
    // Send email
    $mail_sent = mail($to, $email_subject, $body, implode("\r\n", $headers));
    
    if ($mail_sent) {
        $_SESSION['feedback_success'] = 'Thank you for your feedback! We will review it shortly.';
    } else {
        // If mail fails but database entry succeeds
        $_SESSION['feedback_success'] = 'Your feedback has been recorded, but there was an issue sending the email notification. Our team will still review your feedback.';
    }
} catch (Exception $e) {
    $_SESSION['feedback_error'] = 'An error occurred while submitting your feedback. Please try again later.';
}

// Redirect back to the feedback page
header('Location: ../../../page/feedback.php');
exit;
?>
