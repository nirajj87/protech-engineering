<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("invalid");
}

/* ---------------------------
   🔐 FORM SECURITY CHECKS
-----------------------------*/

// Validate required fields
$required = ['name', 'email', 'phone', 'message'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo "missing";
        exit;
    }
}

// Collect data safely
$name     = htmlspecialchars(trim($_POST['name']));
$company  = htmlspecialchars(trim($_POST['company']));
$email    = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$phone    = htmlspecialchars(trim($_POST['phone']));
$service  = htmlspecialchars(trim($_POST['service']));
$message  = htmlspecialchars(trim($_POST['message']));

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "invalid_email";
    exit;
}

/* ---------------------------
   📨 ADMIN EMAIL (HTML)
-----------------------------*/

$to_admin = "protechengineering.imt@gmail.com";
$subject_admin = "📩 New Inquiry From $name - ProTech Engineering";

$htmlMessage_admin = "
<html>
<head>
  <style>
    body { font-family: Arial; color:#333; }
    .box { padding:15px; border:1px solid #ddd; border-radius:5px; }
    .title { font-size:20px; font-weight:bold; margin-bottom:10px; }
  </style>
</head>
<body>
<div class='box'>
  <div class='title'>New Contact Inquiry</div>
  <p><strong>Name:</strong> $name</p>
  <p><strong>Company:</strong> $company</p>
  <p><strong>Email:</strong> $email</p>
  <p><strong>Phone:</strong> $phone</p>
  <p><strong>Service Interested:</strong> $service</p>
  <p><strong>Message:</strong><br>$message</p>
</div>
</body>
</html>
";

// Email headers (required for HTML email)
$headers_admin  = "MIME-Version: 1.0\r\n";
$headers_admin .= "Content-type: text/html; charset=UTF-8\r\n";
$headers_admin .= "From: $name <$email>\r\n";
$headers_admin .= "Reply-To: $email\r\n";

/* ---------------------------
   📧 SEND EMAIL TO ADMIN
-----------------------------*/
$admin_sent = mail($to_admin, $subject_admin, $htmlMessage_admin, $headers_admin);


/* ---------------------------
   🙏 THANK YOU EMAIL TO CUSTOMER
-----------------------------*/

$to_user = $email;
$subject_user = "Thank You for Contacting ProTech Engineering";

$htmlMessage_user = "
<html>
<head>
  <style>
    body { font-family: Arial; color:#333; }
    .box { padding:15px; border:1px solid #ddd; border-radius:5px; }
    .title { font-size:20px; font-weight:bold; color:#0d6efd; }
  </style>
</head>
<body>
<div class='box'>
  <div class='title'>Thank You, $name</div>
  <p>We have received your message and our team will contact you soon.</p>
  <p><strong>ProTech Engineering</strong></p>
</div>
</body>
</html>
";

$headers_user  = "MIME-Version: 1.0\r\n";
$headers_user .= "Content-type: text/html; charset=UTF-8\r\n";
$headers_user .= "From: ProTech Engineering <no-reply@yourdomain.com>\r\n";
$headers_user .= "Reply-To: no-reply@yourdomain.com\r\n";

$user_sent = mail($to_user, $subject_user, $htmlMessage_user, $headers_user);


/* ---------------------------
   🟢 FINAL RESPONSE
-----------------------------*/

if ($admin_sent && $user_sent) {
    echo "success";
} else {
    echo "error";
}

?>
