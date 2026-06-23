<?php
session_start();

// ✅ Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "optics_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection Failed: " . $conn->connect_error);
}

// ✅ Login Handler
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  $user = $_POST['username'];
  $pass = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM user_account WHERE Username = ?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($pass, $row['Password'])) {

      // ✅ Save session (optional)
      $_SESSION['user_id'] = $row['User_ID'];
      $_SESSION['role'] = $row['Role'];
      $_SESSION['username'] = $row['Username'];

      // ✅ Role-based redirect
      if ($row['Role'] === 'Admin') {
        header("Location: dashboard.php"); // full sidebar
        exit();
      } elseif ($row['Role'] === 'Staff') {
        header("Location: staff_dashboard.php"); // limited sidebar
        exit();
      } else {
        header("Location: dashboard.php"); // fallback
        exit();
      }

    } else {
      header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
      exit();
    }

  } else {
    header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
    exit();
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Form</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet" />

  <!-- CSS -->
  <link rel="stylesheet" href="/eyecaree/css/login-design.css" />
  <link rel="stylesheet" href="/eyecaree/css/modal-designs.css" />
</head>
<body>
  <div class="container">

    <!-- Logo -->
    <div class="namecomp">
      <img src="logos/beter.png" alt="Eyecare Optical Logo" />
    </div>

    <!-- Login Form -->
    <div class="form-box">
      <h2>Login</h2>
      <form action="" method="POST">
        <input type="text" name="username" placeholder="USERNAME" required />
        <input type="password" name="password" placeholder="PASSWORD" required />
        <button type="submit" name="login">LOGIN</button>
      </form>
    </div>
  </div>

  <!-- Modal for Invalid Login -->
  <div id="errorModal" class="modal">
    <div class="modal-content">
      <p>Invalid username or password!</p>
      <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
  </div>

  <!-- JS for Modal -->
  <script>
    function closeModal() {
      document.getElementById("errorModal").style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function () {
      const urlParams = new URLSearchParams(window.location.search);
      const error = urlParams.get('error');

      if (error === '1' && performance.getEntriesByType("navigation")[0].type === "navigate") {
        document.getElementById("errorModal").style.display = "block";
      }
    });
  </script>
</body>
</html>
