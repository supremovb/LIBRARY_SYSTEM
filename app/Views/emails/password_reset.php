<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
</head>
<body>
    <p>Hello <?= esc($firstname); ?>,</p> <!-- Use $firstname here -->
    <p>You have requested to reset your password. Click the link below to reset it:</p>
    <p><a href="<?= esc($resetLink); ?>">Reset Password</a></p> <!-- Use $resetLink here -->
    <p>This link will expire in 5 minutes.</p>
    <p>If you did not request a password reset, please ignore this email.</p>
    <p>Thank you,<br>Library System Team</p>
</body>
</html>
