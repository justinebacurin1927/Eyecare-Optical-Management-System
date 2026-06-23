<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="eyecaree/css/style.css">
</head>
<body>
    <img src="beter.png" class="namecomp">
    <div class="form-box">
        <form action="change-password-process.php" method="POST">
            <h2><b>Change Password</b></h2>
            <input type="password" name="OLD_PASSWORD" placeholder="Old Password" required>
            <input type="password" name="NEW_PASSWORD" placeholder="New Password" required>
            <input type="password" name="CONFIRM_NEW_PASSWORD" placeholder="Confirm New Password" required>
            <button type="submit">CHANGE PASSWORD</button>
            <p><a href="login.html">Back to Login</a></p>
        </form>
    </div>
</body>
</html>
