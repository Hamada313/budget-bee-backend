<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        .header {
            text-align: center;
            background-color: #e3fcef;
            padding: 20px;
        }
        .header img {
            max-width: 80px;
        }
        .header h1 {
            font-size: 24px;
            color: #333333;
            margin: 10px 0 0;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #666666;
            margin: 10px 0;
        }
        .otp {
            font-size: 32px;
            color: #28a745;
            font-weight: bold;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999999;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        {{-- <img src="{{ asset('images/logo.png') }}" alt="App Logo"> --}}
        <h1>BUDGETBEE</h1>
    </div>
    <div class="content">
        <p>Every Expense is tracked</p>
        <p>Use the OTP below to {{$type == 'verification' ? 'verifiy your account' : 'reset your password'}}. This code is valid for the next 10 minutes:</p>
        <div class="otp">{{ $otp }}</div>
        <a href="{{ $verificationLink }}" class="button">Verify</a>
        <p>If you did not request this, please ignore this email.</p>
    </div>
    <div class="footer">
        <p>Version 1.0.0</p>
    </div>
</div>
</body>
</html>
