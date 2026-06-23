<?php

//  ✅ Database Connection
$conn = new mysqli("localhost", "root", "", "optics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//  ✅ Toggle Status Handler
if (isset($_GET['toggle_status'])) {
    $userId = intval($_GET['toggle_status']);
    $result = $conn->query("SELECT Status FROM User_Account WHERE User_ID = $userId");
    if ($result && $row = $result->fetch_assoc()) {
        $newStatus = ($row['Status'] === 'Active') ? 'Inactive' : 'Active';
        $conn->query("UPDATE User_Account SET Status = '$newStatus' WHERE User_ID = $userId");
        header("Location: account_page.php");
        exit();
    }
}

//  ✅ Delete User Handler
if (isset($_GET['delete_user'])) {
    $userId = intval($_GET['delete_user']);
    $conn->query("DELETE FROM User_Account WHERE User_ID = $userId");
    header("Location: account_page.php");
    exit();
}

$errors = [];
$success = false;

//  ✅ Handle Edit User
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit_user_id'])) {
    $userId = intval($_POST['edit_user_id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    if ($username && $email && $role && $status) {
        $stmt = $conn->prepare("UPDATE User_Account SET Username = ?, Email = ?, Role = ?, Status = ? WHERE User_ID = ?");
        $stmt->bind_param("ssssi", $username, $email, $role, $status, $userId);
        try {
            $stmt->execute();
            header("Location: account_page.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            $errors[] = $e->getMessage();
        }
        $stmt->close();
    } else {
        $errors[] = "All fields are required for editing.";
    }
}

//  ✅ Handle Add User
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['edit_user_id'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    if ($username && $email && $password && $role && $status) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO User_Account (Username, Email, Password, Role, Status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $role, $status);

        try {
            $stmt->execute();
            $success = true;
        } catch (mysqli_sql_exception $e) {
            $errors[] = $e->getMessage();
        }

        $stmt->close();
    } else {
        $errors[] = "All fields are required.";
    }
}

//  ✅ Fetch All Users
$users = [];
$result = $conn->query("SELECT User_ID, Username, Email, Role, Status FROM User_Account");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css" />
  <link rel="stylesheet" href="/eyecaree/css/styles.css" />
  <link rel="stylesheet" href="/eyecaree/css/account.css" />
  <link rel="stylesheet" href="/eyecaree/css/category.css" />
  <link rel="stylesheet" href="/eyecaree/css/bodylayout.css" />
</head>
<body>

<style>
  .btn-edit,
  .btn-delete {
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s ease;
    margin-right: 5px;
  }

  .btn-edit {
    background-color: #0d6efd;
  }

  .btn-edit:hover {
    background-color: #0b5ed7;
  }

  .btn-delete {
    background-color: #dc3545;
  }

  .btn-delete:hover {
    background-color: #bb2d3b;
  }
</style>

<!-- Sidebar -->
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">

  <!-- Header Panel -->
  <div class="panel panel-headers">
    <div class="headermenu-title">
      <img class="headerslogo" src="logos/account.svg" alt="panel">
      <h3 class="headerstitle">User Management</h3>
    </div>
  </div>

  <!-- Alert Message -->
  <?php if ($errors): ?>
    <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success">User added successfully!</div>
  <?php endif; ?>

  <!-- Add User Button -->
  <button class="btn btn-success btn-add-user" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>

  <!-- User Table -->
  <div class="panel-categories">
    <table class="table table-bordered user-table">
      <thead class="table-light">
        <tr>
          <th class="table-success">Username</th>
          <th class="table-success">Email</th>
          <th class="table-success">Role</th>
          <th class="table-success">Status</th>
          <th class="table-success">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['Username']) ?></td>
            <td><?= htmlspecialchars($user['Email']) ?></td>
            <td><?= htmlspecialchars($user['Role']) ?></td>
            <td>
              <a href="?toggle_status=<?= $user['User_ID'] ?>" class="btn-status <?= $user['Status'] === 'Active' ? 'active' : 'inactive' ?>">
                <?= $user['Status'] ?>
              </a>
            </td>
            <td>
              <button class="btn-edit"
                data-bs-toggle="modal"
                data-bs-target="#editUserModal"
                data-user-id="<?= $user['User_ID'] ?>"
                data-username="<?= htmlspecialchars($user['Username']) ?>"
                data-email="<?= htmlspecialchars($user['Email']) ?>"
                data-role="<?= $user['Role'] ?>"
                data-status="<?= $user['Status'] ?>">
                Edit
              </button>

              <form method="GET" action="account_page.php" style="display:inline;">
                <input type="hidden" name="delete_user" value="<?= $user['User_ID'] ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')" class="btn-delete">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="account_page.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="username" placeholder="Username" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <select name="role" required>
            <option value="">Select Role</option>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Doctor">Doctor</option>
          </select>
          <select name="status" required>
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save User</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="account_page.php">
      <input type="hidden" name="edit_user_id" id="editUserId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="username" id="editUsername" placeholder="Username" required>
          <input type="email" name="email" id="editEmail" placeholder="Email" required>
          <select name="role" id="editRole" required>
            <option value="Admin">Admin</option>
            <option value="Staff">Staff</option>
            <option value="Doctor">Doctor</option>
          </select>
          <select name="status" id="editStatus" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const editModal = document.getElementById('editUserModal');
  editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('editUserId').value = button.getAttribute('data-user-id');
    document.getElementById('editUsername').value = button.getAttribute('data-username');
    document.getElementById('editEmail').value = button.getAttribute('data-email');
    document.getElementById('editRole').value = button.getAttribute('data-role');
    document.getElementById('editStatus').value = button.getAttribute('data-status');
  });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
