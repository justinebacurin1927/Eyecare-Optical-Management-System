<?php
include 'includes/sidebar.php';


// DB connection

$conn = new mysqli('localhost', 'root', '', 'optics_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

 
// Insert product

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Product_Name'])) {
  $stmt = $conn->prepare("INSERT INTO Product (Product_Name, Description, Category_ID, Quantity, Selling_Price, Discounted_Price, Reorder_Level, Reorder_Quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssiidddi", $_POST['Product_Name'], $_POST['Description'], $_POST['Category_ID'], $_POST['Quantity'], $_POST['Selling_Price'], $_POST['Discounted_Price'], $_POST['Reorder_Level'], $_POST['Reorder_Quantity']);
  if ($stmt->execute()) {
    header("Location: " . $_SERVER['PHP_SELF'] . "#ProductListTab");
    exit();
  } else {
    echo "Insert error: " . $stmt->error;
  }
}

 
// Delete product

if (isset($_POST['delete_id'])) {
  $conn->query("DELETE FROM Product WHERE Product_ID = " . intval($_POST['delete_id']));
  header("Location: " . $_SERVER['PHP_SELF'] . "#ProductListTab");
  exit();
}

 
// Add lens type

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_lens'])) {
  $stmt = $conn->prepare("INSERT INTO lens_type (Lens_Type_Name, Lens_Material, Lens_Coating) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $_POST['lens_type_name'], $_POST['lens_material'], $_POST['lens_coating']);
  $stmt->execute();
}

 
// Add frame

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_frame'])) {
  $stmt = $conn->prepare("INSERT INTO frame (Frame_Name, Frame_Brand, Frame_Material, Frame_Style, Frame_Size) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $_POST['frame_name'], $_POST['frame_brand'], $_POST['frame_material'], $_POST['frame_style'], $_POST['frame_size']);
  $stmt->execute();
}

 
// Get categories

$categories = $conn->query("SELECT * FROM Categories");

function renderAddProductTab($categories) {
  ?>
  <div id="AddProductTab" class="tabcontent" style="display:block;">
    <div class="headermenu-title">
      <img class="headerslogo" src="logos/product.svg" alt="add">
      <h3 class="headerstitle"> Add Product</h3>
    </div>
    <div class="panel">
      <form method="post">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="Product_Name" class="form-label">Product Name:</label>
              <input name="Product_Name" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="Description" class="form-label">Description:</label>
              <input name="Description" type="text" class="form-control">
            </div>
            <div class="mb-3">
              <label for="Category_ID" class="form-label">Category:</label>
              <select name="Category_ID" class="form-select" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                  <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="Quantity" class="form-label">Quantity:</label>
              <input name="Quantity" type="number" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="Selling_Price" class="form-label">Selling Price:</label>
              <input name="Selling_Price" type="number" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="Discounted_Price" class="form-label">Discounted Price:</label>
              <input name="Discounted_Price" type="number" step="0.01" class="form-control">
            </div>
            <div class="mb-3">
              <label for="Reorder_Level" class="form-label">Reorder Level:</label>
              <input name="Reorder_Level" type="number" class="form-control">
            </div>
            <div class="mb-3">
              <label for="Reorder_Quantity" class="form-label">Reorder Quantity:</label>
              <input name="Reorder_Quantity" type="number" class="form-control">
            </div>
          </div>
        </div>
        <div class="text-end mt-3">
          <button class="btn btn-danger me-2" type="reset">Reset</button>
          <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
  <?php
}

function renderProductListTab($conn) {
  $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

  if (!empty($searchTerm)) {
    $stmt = $conn->prepare("SELECT * FROM Product WHERE Product_ID = ? OR Product_Name LIKE CONCAT('%', ?, '%') ORDER BY Product_ID DESC");
    $stmt->bind_param("is", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
  } else {
    $result = $conn->query("SELECT * FROM Product ORDER BY Product_ID DESC");
  }
  ?>
  <div id="ProductListTab" class="tabcontent">
    <div class="headermenu-title">
      <h3><img class="headerslogo" src="logos/panel.svg" alt="list"> Product List</h3>
    </div>

    <form method="GET" class="button-row">
      <input type="text" name="search" class="search-form" placeholder="Search for ID or Name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
      <button type="submit" class="btn-search">Search</button>
      <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn-show">Show All</a>
    </form>

    <div class="panel">
      <table class="table table-bordered">
        <thead class="table-success">
          <tr>
            <th>ID</th><th>Name</th><th>Qty</th><th>Price</th><th>Discount</th>
            <th>Reorder Level</th><th>Reorder Qty</th><th>Added</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['Product_ID'] ?></td>
            <td><?= htmlspecialchars($row['Product_Name']) ?></td>
            <td><?= $row['Quantity'] ?></td>
            <td>₱<?= $row['Selling_Price'] ?></td>
            <td>₱<?= $row['Discounted_Price'] ?? '-' ?></td>
            <td><?= $row['Reorder_Level'] ?></td>
            <td><?= $row['Reorder_Quantity'] ?></td>
            <td><?= $row['Date_Added'] ?></td>
            <td>
              <form method="post" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?= $row['Product_ID'] ?>">
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php
}

function renderLensFrameTab() {
  ?>
  <div id="LensFrameTab" class="tabcontent">
    <div class="headermenu-title">
      <h3><img class="headerslogo" src="logos/panel.svg" alt="frame"> Frame & Lens Inventory</h3>
    </div>

    <div class="panel">
      <form method="post" class="inputtrans2">
        <h5>Add Frame</h5>
        <input name="frame_name" placeholder="Frame Name" required>
        <input name="frame_brand" placeholder="Brand">
        <input name="frame_material" placeholder="Material">
        <input name="frame_style" placeholder="Style">
        <input name="frame_size" placeholder="Size">
        <button type="submit" name="add_frame" class="itembtn2">Add Frame</button>
      </form>
    </div>

    <div class="panel">
      <form method="post" class="inputtrans2" style="margin-top: 30px;">
        <h5>Add Lens Type</h5>
        <input name="lens_type_name" placeholder="Lens Type Name" required>
        <input name="lens_material" placeholder="Material">
        <input name="lens_coating" placeholder="Coating">
        <button type="submit" name="add_lens" class="itembtn2">Add Lens Type</button>
      </form>
    </div>
  </div>
  <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/css/sidebar-design.css">
  <link rel="stylesheet" href="/eyecaree/css/products-design.css">
  <link rel="stylesheet" href="/eyecaree/css/bodylayout.css">
</head>
<body>

<style>
.search-form {
    width: 25%;
    padding: 10px;
    border-radius: 30px;
    border: 1px solid black;
    margin-left: 20px;
}

.button-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.btn-show {
    width: 15%;
    padding: 10px;
    color: white;
    background-color: blue;
    border-radius: 30px;
    border: 1px solid black;
    text-align: center;
    text-decoration: none;
}

.btn-search {
    width: 15%;
    padding: 10px;
    color: black;
    background-color: yellow;
    border-radius: 30px;
    border: 1px solid black;
}
</style>

<div class="layout-container">
  <div class="main-content">
    <div class="tab">
      <a href="#AddProductTab" class="tablinks active" onclick="switchTab(event, 'AddProductTab')">Add Product</a>
      <a href="#ProductListTab" class="tablinks" onclick="switchTab(event, 'ProductListTab')">Product List</a>
      <a href="#LensFrameTab" class="tablinks" onclick="switchTab(event, 'LensFrameTab')">Frame & Lens</a>
    </div>

    <?php renderAddProductTab($categories); ?>
    <?php renderProductListTab($conn); ?>
    <?php renderLensFrameTab(); ?>
  </div>
</div>

<script>
function switchTab(evt, tabName) {
  const tabs = document.getElementsByClassName("tabcontent");
  for (let i = 0; i < tabs.length; i++) tabs[i].style.display = "none";
  const buttons = document.getElementsByClassName("tablinks");
  for (let i = 0; i < buttons.length; i++) buttons[i].classList.remove("active");
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.classList.add("active");
  window.location.hash = tabName;
}

window.addEventListener('DOMContentLoaded', () => {
  const hash = window.location.hash.substring(1);
  if (hash) {
    const tab = document.getElementById(hash);
    const button = document.querySelector(`.tablinks[href="#${hash}"]`);
    if (tab && button) switchTab({ currentTarget: button }, hash);
  }
});
</script>

</body>
</html>
