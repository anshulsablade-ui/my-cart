<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 50px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #007bff;
            text-align: center;
            margin-top: 20px;
        }

        p {
            color: #000000;
            margin-top: 20px;
        }

        a {
            display: block;
            width: 200px;
            height: 40px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            line-height: 40px;
            text-decoration: none;
            margin: 20px auto;
            border-radius: 5px;
        }

        a:hover {
            background-color: #0056b3;
            color: #fff;
        }

        p:last-child {
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Password Reset Notification</h3>
        <p>Hi {{ ucfirst($user_name) }},</p>
        <p>Someone requested a password reset for your account. If this was you, please click the link below to reset your password:</p>
        <div style="text-align: center;"><a href="{{ route('reset-password', $token) }}">Reset Password</a></div>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you,</p>
        <p>{{env('APP_NAME')}}</p>
    </div>
</body>

</html>