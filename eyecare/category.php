<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'optics_db';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

  
// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'], $_POST['description']) && !isset($_POST['edit_id'])) {
  $name = $_POST['category_name'];
  $desc = $_POST['description'];

  $stmt = $conn->prepare("INSERT INTO categories (category_name, description) VALUES (?, ?)");
  $stmt->bind_param("ss", $name, $desc);
  $stmt->execute();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

  
// Handle Edit Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['category_name'], $_POST['description'])) {
  $editId = $_POST['edit_id'];
  $name = $_POST['category_name'];
  $desc = $_POST['description'];

  $stmt = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?");
  $stmt->bind_param("ssi", $name, $desc, $editId);
  $stmt->execute();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

  
// Handle Delete Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $deleteId = $_POST['delete_id'];
  $stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
  $stmt->bind_param("i", $deleteId);
  $stmt->execute();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

  
// Handle Search
$searchTerm = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_term'])) {
  $searchTerm = trim($_POST['search_term']);
}

  
// Query
$sql = "SELECT c.*, COUNT(p.Product_ID) AS product_count 
        FROM categories c 
        LEFT JOIN product p ON c.category_id = p.Category_ID";
if ($searchTerm !== '') {
  $sql .= " WHERE c.category_id LIKE '%$searchTerm%' OR c.category_name LIKE '%$searchTerm%'";
}
$sql .= " GROUP BY c.category_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Categories Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css" />
  <link rel="stylesheet" href="/eyecaree/css/styles.css" />
  <link rel="stylesheet" href="/eyecare/css/category.css" />
  <link rel="stylesheet" href="/eyecaree/css/bodylayout.css" />
</head>
<body>

<style>
.btn-edit {
  background-color: #0d6efd;
  color: #fff;
  border: none;
  padding: 6px 12px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  text-transform: uppercase;
}
.btn-edit:hover { background-color: #0b5ed7; }

.btn-delete {
  background-color: #dc3545;
  color: #fff;
  border: none;
  padding: 6px 12px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  text-transform: uppercase;
}
.btn-delete:hover { background-color: #bb2d3b; }

.button-show {
  width: 15%;
  padding: 10px;
  color: white;
  background-color: blue;
  border-radius: 30px;
  border: 1px solid black;
  text-align: center;
  text-decoration: none;
  display: inline-block;
}
.btn-search {
    width: 15%;
    padding: 10px;
    color: white;
    background-color: #35435f   ;
    border-radius: 30px;
    border: 1px solid black
}
</style>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
  <div class="panel panel-headers">
    <div class="headermenu-title">
      <img class="headerslogo" src="logos/panel.svg" alt="panel">
      <h3 class="headerstitle">Categories Panel</h3>
    </div>
  </div>

  <div class="button-row">
    <input type="text" name="search_term" class="search-form" placeholder="Search for ID or Name" value="<?= htmlspecialchars($searchTerm) ?>" form="searchForm">
    <button type="submit" class="btn-search" form="searchForm">Search</button>
    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="button-show">Show All</a>
    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">Add Category</button>
  </div>

  <form id="searchForm" method="POST" style="display:none;"></form>

  <div class="panel panel-categories">
    <div class="category-table-layout">
      <table class="table table-bordered category-table">
        <thead>
          <tr>
            <th class="table-success">ID</th>
            <th class="table-success">Category Name</th>
            <th class="table-success">Description</th>
            <th class="table-success">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['category_id'] ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                  <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal"
                    data-category-id="<?= $row['category_id'] ?>"
                    data-category-name="<?= htmlspecialchars($row['category_name']) ?>"
                    data-description="<?= htmlspecialchars($row['description']) ?>">EDIT</button>

                  <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal"
                    data-category-id="<?= $row['category_id'] ?>"
                    data-product-count="<?= $row['product_count'] ?>">DELETE</button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">No categories found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="category_name" placeholder="Category Name" required>
          <textarea name="description" placeholder="Description" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <input type="hidden" name="edit_id" id="editCategoryId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="category_name" id="editCategoryName" required>
          <textarea name="description" id="editDescription" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post">
      <input type="hidden" name="delete_id" id="deleteCategoryId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this category?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Warning Modal -->
<div class="modal fade" id="deleteWarningModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Cannot Delete Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-danger">
        This category has linked products. Please delete them first.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const deleteModal = document.getElementById('deleteModal');
  const warningModal = new bootstrap.Modal(document.getElementById('deleteWarningModal'));

  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const productCount = parseInt(button.getAttribute('data-product-count'), 10);
    if (productCount > 0) {
      event.preventDefault();
      warningModal.show();
    } else {
      document.getElementById('deleteCategoryId').value = button.getAttribute('data-category-id');
    }
  });

  const editModal = document.getElementById('editModal');
  editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('editCategoryId').value = button.getAttribute('data-category-id');
    document.getElementById('editCategoryName').value = button.getAttribute('data-category-name');
    document.getElementById('editDescription').value = button.getAttribute('data-description');
  });
</script>

</body>
</html>
