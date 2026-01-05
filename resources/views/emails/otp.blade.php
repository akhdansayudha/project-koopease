<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password OTP</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 40px;
            margin-bottom: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #2563eb;
            /* Biru KoopEase */
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .content {
            padding: 40px 30px;
            text-align: center;
            color: #333333;
        }

        .otp-box {
            background-color: #f0f7ff;
            border: 2px dashed #2563eb;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }

        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: 5px;
            margin: 0;
        }

        .warning {
            font-size: 12px;
            color: #666666;
            margin-top: 10px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>KoopEase</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="font-size: 20px; margin-bottom: 10px;">Permintaan Reset Kata Sandi</h2>
            <p style="font-size: 14px; line-height: 1.6;">
                Halo, kami menerima permintaan untuk mereset kata sandi akun KoopEase Anda.
                Gunakan kode OTP di bawah ini untuk melanjutkan proses reset password.
            </p>

            <!-- OTP Box -->
            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p class="warning">
                Kode ini hanya berlaku selama <strong>5 menit</strong>.<br>
                Jika Anda tidak merasa meminta reset password, abaikan email ini.
            </p>

            <p style="margin-top: 30px; font-size: 14px;">
                Terima kasih,<br>
                <strong>Tim KoopEase</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} KoopEase. All rights reserved.<br>
            Email ini dikirim secara otomatis, mohon tidak membalas.
        </div>
    </div>
</body>

</html>
