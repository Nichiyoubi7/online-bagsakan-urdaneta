<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
    .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .logo { font-size: 28px; font-weight: 900; color: #16a34a; margin-bottom: 24px; }
    h1 { font-size: 22px; color: #111; margin-bottom: 8px; }
    p { color: #666; font-size: 14px; line-height: 1.6; }
    .otp-box { background: #f0fdf4; border: 2px dashed #16a34a; border-radius: 12px; text-align: center; padding: 24px; margin: 24px 0; }
    .otp-code { font-size: 40px; font-weight: 900; letter-spacing: 12px; color: #16a34a; }
    .footer { color: #aaa; font-size: 12px; margin-top: 24px; text-align: center; }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">OBRA</div>
    <h1>Verify your email address</h1>
    <p>Thanks for signing up! Use the code below to verify your email address. This code expires in <strong>10 minutes</strong>.</p>
    <div class="otp-box">
      <div class="otp-code">{{ $otp }}</div>
    </div>
    <p>If you did not create an account, you can safely ignore this email.</p>
    <div class="footer">© 2026 OBRA - Online Bagsakan Urdaneta. All rights reserved.</div>
  </div>
</body>
</html>
