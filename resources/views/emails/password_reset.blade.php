<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Hello, {{ $email }}!</h2>
    <p>We received a request to reset the password for your account.</p>
    <p>Click the button below to choose a new password. This link is valid for 60 minutes.</p>


    <p>To reset your password, please use the token below in your API request:</p>
    <div style="background: #f4f4f4; padding: 10px; font-family: monospace; font-size: 1.2em;">
        {{ $token }}
    </div>
    <p>Alternatively, if you have a frontend running:</p>
    <a href="{{ config('app.frontend_url') }}/reset-password?token={{ $token }}&email={{ $email }}">
        Reset Password
    </a>

    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,<br>Library Management System</p>
</body>

</html>
