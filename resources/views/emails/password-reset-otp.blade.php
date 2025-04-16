<!-- resources/views/emails/password-reset-otp.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            padding: 15px;
            background-color: #eaeaea;
            border-radius: 4px;
            margin: 20px 0;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>We received a request to reset your password. Please use the following One-Time Password (OTP) to complete
            the password reset process:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This OTP will expire in 30 minutes. If you did not request a password reset, please ignore this email.</p>
        <p>For security reasons, do not share this OTP with anyone.</p>
    </div>
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>

</html>
