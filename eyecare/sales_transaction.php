<?php

//  =====================
//  AJAX Handlers
//  =====================


//  Handle AJAX Product Check

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_product'])) {
  $conn = new mysqli("localhost", "root", "", "optics_db");
  header('Content-Type: application/json');
  $pid = intval($_POST['product_id']);
  $res = $conn->query("SELECT Product_Name, Selling_Price FROM Product WHERE Product_ID=$pid");

  if ($res && $res->num_rows) {
    $r = $res->fetch_assoc();
    echo json_encode(["exists" => true, "name" => $r['Product_Name'], "unit_price" => $r['Selling_Price']]);
  } else echo json_encode(["exists" => false]);

  exit;
}

//  Handle AJAX Submit Transaction

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_transaction'])) {
  $conn = new mysqli("localhost", "root", "", "optics_db");
  header('Content-Type: application/json');
  $p = $_POST['Patient_ID'];
  $d = $_POST['Transaction_Date'];
  $tot = floatval($_POST['Total_Amount']);
  $ps = $_POST['Payment_Status'];
  $items = json_decode($_POST['Item_List'], true);
  $conn->query("INSERT INTO sale_transaction (Patient_ID, Transaction_Date, Total_Amount, Discount_Amount, Payment_Status, Created_At)
                VALUES ('$p','$d',$tot,0,'$ps',NOW())");

  $sid = $conn->insert_id;
  foreach ($items as $it) {
    $conn->query("INSERT INTO sale_item (Sale_ID, Product_ID, Quantity_Sold, Unit_Price, Total_Price)
                  VALUES ($sid,{$it['productID']},{$it['quantity']},{$it['unitPrice']},{$it['totalPrice']})");
  }
  echo json_encode(["success" => true]);
  exit;
}

//  Handle AJAX Delete

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $conn = new mysqli("localhost", "root", "", "optics_db");
  $id = intval($_POST['delete_id']);
  $conn->query("DELETE FROM sale_item WHERE Sale_ID=$id");
  $conn->query("DELETE FROM sale_transaction WHERE Sale_ID=$id");
  echo json_encode(["deleted" => true]);
  exit;
}


//  Handle AJAX Get Sale Data for Edit Modal

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_sale_data'], $_GET['sale_id'])) {
  $conn = new mysqli("localhost", "root", "", "optics_db");
  header('Content-Type: application/json');
  $sid = intval($_GET['sale_id']);
  $res = $conn->query("SELECT Sale_ID, Patient_ID, Transaction_Date, Payment_Status FROM sale_transaction WHERE Sale_ID=$sid LIMIT 1");

  if ($res && $res->num_rows) {
    $data = $res->fetch_assoc();
    echo json_encode(["success" => true, "sale" => $data]);
  } else {
    echo json_encode(["success" => false]);
  }

  exit;
}


//  Handle AJAX Update Sale Transaction

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_sale'])) {
  $conn = new mysqli("localhost", "root", "", "optics_db");
  header('Content-Type: application/json');
  $saleID = intval($_POST['Sale_ID']);
  $patientID = intval($_POST['Patient_ID']);
  $date = $_POST['Transaction_Date'];
  $paymentStatus = $conn->real_escape_string($_POST['Payment_Status']);

  $updateSQL = "UPDATE sale_transaction SET Patient_ID=$patientID, Transaction_Date='$date', Payment_Status='$paymentStatus' WHERE Sale_ID=$saleID";

  if ($conn->query($updateSQL)) {
    echo json_encode(["success" => true]);
  } else {
    echo json_encode(["success" => false, "error" => $conn->error]);
  }

  exit;
}

// Page Setup: Fetch sales with pagination & search //  

$conn = new mysqli("localhost", "root", "", "optics_db");

$search = $_GET['search'] ?? '';
$order = ($_GET['sort'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$nextOrder = $order === 'ASC' ? 'desc' : 'asc';
$page = intval($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

//  Total count for pagination

$count_sql = "SELECT COUNT(*) as total FROM sale_transaction st LEFT JOIN patient p USING(Patient_ID)";
if ($search) {
  $s = $conn->real_escape_string($search);
  $count_sql .= " WHERE st.Sale_ID LIKE '%$s%' OR p.First_Name LIKE '%$s%' OR p.Last_Name LIKE '%$s%'";
}
$total_result = $conn->query($count_sql);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

//  Sales Query

$sql = "SELECT st.*, p.First_Name, p.Last_Name FROM sale_transaction st LEFT JOIN patient p USING(Patient_ID)";
if ($search) {
  $s = $conn->real_escape_string($search);
  $sql .= " WHERE st.Sale_ID LIKE '%$s%' OR p.First_Name LIKE '%$s%' OR p.Last_Name LIKE '%$s%'";
}
$sql .= " ORDER BY st.Sale_ID $order LIMIT $limit OFFSET $offset";
$sales = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <title>Sales Transaction Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css">
  <link rel="stylesheet" href="/eyecaree/css/styles.css">
  <link rel="stylesheet" href="/eyecaree/css/.css">

  <style>

    body {
      margin: 0;
      padding: 0;
    }

    .layout-container {
      display: flex;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      width: calc(100% - 250px);
    }

    .panel {
      background: #2c3e50;
      color: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 25px;
    }

    .tab {
      background: #c5d8f4;
      padding: 10px;
      margin-bottom: 15px;
      display: flex;
      gap: 10px;
    }

    .tab a {
      padding: 10px 20px;
      text-decoration: none;
      color: black;
      background: #e0e0e0;
      border-radius: 5px;
    }

    .tab a.active {
      background: #85abf2;
      font-weight: bold;
    }

    .tabcontent {
      display: none;
    }

    .tabcontent.active {
      display: block;
    }

    .input-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
    }

    .ITEM {
      margin-top: 20px;
      font-weight: bold;
      font-size: 18px;
    }

    .ITEM2 {
      text-align: right;
      font-weight: bold;
      font-size: 18px;
      color: #fbfaf8ff;
    }

    .itembtn,
    .itembtn1,
    .itembtn2 {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      color: white;
      margin-top: 15px;
    }

    .itembtn {
      background: #198754;
    }

    .itembtn1 {
      background: #dc3545;
      margin-right: 10px;
    }

    .itembtn2 {
      background: #0d6efd;
    }

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
      margin-left: 20px;
    }

    .btn-show,
    .btn-search,
    .btn-sort {
      padding: 10px;
      border-radius: 30px;
      border: 1px solid black;
      text-align: center;
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
      display: inline-block;
    }

    .btn-search {
      width: 15%;
      background-color: yellow;
      color: black;
    }

    .btn-sort {
      width: 15%;
      padding: 10px;
      color: white;
      background-color: gray;
      border-radius: 30px;
      border: 1px solid black;
      text-align: center;
      text-decoration: none;
      display: inline-block;
    }

  </style>

</head>

<body>

  <?php include 'includes/sidebar.php'; ?>

  <div class="layout-container">

    <!-- Sidebar is included via PHP -->

    <div class="main-content">
      <div class="tab">
        <a href="#TransactionTab" class="tablinks active" onclick="switchTab(event, 'TransactionTab')">Add Transaction</a>
        <a href="#SalesListTab" class="tablinks" onclick="switchTab(event, 'SalesListTab')">Sales List</a>
      </div>

      <!-- Add Transaction Tab -->

      <div id="TransactionTab" class="tabcontent active">
        <div id="successAlert" class="alert alert-success d-none">Transaction submitted successfully!</div>
        <form id="transactionForm" class="panel input-grid">
          <div>

            <label>Patient</label>
            <select name="Patient_ID" class="form-control" required>
              <option value="">-- Select Patient --</option>

              <?php
                $pp = $conn->query("SELECT Patient_ID, First_Name, Last_Name FROM patient");
                while ($r = $pp->fetch_assoc()) {
                  echo "<option value='{$r['Patient_ID']}'>{$r['Patient_ID']} - {$r['First_Name']} {$r['Last_Name']}</option>";
                }
              ?>

            </select>

          </div>
          <div><label>Transaction Date</label><input type="date" name="Transaction_Date" class="form-control" required></div>
          <div><label>Total Amount</label><input type="text" id="total_amount" name="Total_Amount" class="form-control" readonly value="0.00"></div>
          <div><label>Payment Status</label>

            <select name="Payment_Status" class="form-control">
              <option>Paid</option>
              <option>Pending</option>
            </select>

          </div>

          <input type="hidden" name="Item_List" id="item_list_data">
          <div style="grid-column: span 4;"><button type="submit" class="itembtn2">Submit Transaction</button></div>
        </form>

        <div class="panel">
          <h5 class="ITEM">ITEM INFORMATION</h5>
          <h5 class="ITEM2">Total: <span id="total_sales_display">₱0.00</span></h5>
        </div>

        <form class="panel input-grid" id="itemForm">
          <div><label>Product ID</label>
            <div class="d-flex">
              <input type="text" id="product_id" class="form-control me-2">
              <button type="button" class="btn btn-warning btn-sm" onclick="checkProduct()">Check</button>
            </div>

          </div>
          <div><label>Product Name</label><input type="text" id="product_name" class="form-control" readonly></div>
          <div><label>Quantity</label><input type="number" id="quantity" class="form-control" onchange="updateTotalPrice()"></div>
          <div><label>Unit Price</label><input type="number" id="unit_price" step="0.01" class="form-control" onchange="updateTotalPrice()"></div>
          <div><label>Total Price</label><input type="number" id="total_price" class="form-control" readonly></div>
          <div><label>Discount</label>

            <select id="discount_percent" class="form-control">
              <option value="0">0%</option>
              <option value="0.2">20%</option>
              <option value="0.5">50%</option>
              <option value="0.7">70%</option>
            </select>

          </div>

          <div style="grid-column: span 4;">
            <button type="button" class="itembtn" onclick="addItemToList()">Confirm Item</button>
            <button type="reset" class="itembtn1">Cancel</button>
          </div>

        </form>
      </div>

      <!-- Sales List Tab -->

      <div id="SalesListTab" class="tabcontent">
        <form method="GET" class="button-row">
          <input type="text" name="search" class="search-form" placeholder="Search Sale ID or Patient" value="<?= htmlspecialchars($search) ?>">
          <button type="submit" class="btn-search">Search</button>
          <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="btn-show">Show All</a>
          <a href="?sort=<?= $nextOrder ?>&search=<?= urlencode($search) ?>" class="btn-sort">Sort by ID ↑↓</a>
        </form>

        <div class="panel">
          <table id="salesTable" class="table table-bordered table-striped text-dark">
            <thead>

              <tr>
                <th class="table-success">Sale ID</th>
                <th class="table-success">Patient</th>
                <th class="table-success">Date</th>
                <th class="table-success">Total</th>
                <th class="table-success">Action</th>
              </tr>

            </thead>
            <tbody>

              <?php foreach ($sales->fetch_all(MYSQLI_ASSOC) as $row): ?>
                <tr>
                  <td><?= $row['Sale_ID'] ?></td>
                  <td><?= htmlspecialchars($row['First_Name'] . ' ' . $row['Last_Name']) ?></td>
                  <td><?= $row['Transaction_Date'] ?></td>
                  <td>₱<?= number_format($row['Total_Amount'], 2) ?></td>
                  <td class="d-flex gap-1 justify-content-center">

                    <button class="btn btn-primary btn-sm" onclick="editSale(<?= $row['Sale_ID'] ?>)">EDIT</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteSale(<?= $row['Sale_ID'] ?>)">DELETE</button>
                  </td>
                </tr>

              <?php endforeach; ?>
            </tbody>
          </table>

          <div class="d-flex justify-content-center mt-3">
            <nav>
              
              <ul class="pagination">
                <?php if ($page > 1): ?><li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Prev</a></li><?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                  <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a></li>

                <?php endfor; ?>
                <?php if ($page < $total_pages): ?><li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a></li><?php endif; ?>

              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Sale Modal -->

  <div class="modal fade" id="editSaleModal" tabindex="-1" aria-labelledby="editSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="editSaleForm">
          <div class="modal-header">
            <h5 class="modal-title" id="editSaleModalLabel">Edit Sale Transaction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="Sale_ID" id="edit_sale_id">
            <div class="mb-3">
              <label for="edit_patient_id" class="form-label">Patient</label>
              <select name="Patient_ID" id="edit_patient_id" class="form-control" required>
                <option value="">-- Select Patient --</option>

                <?php
                  $pp = $conn->query("SELECT Patient_ID, First_Name, Last_Name FROM patient");
                  while ($r = $pp->fetch_assoc()) {
                    echo "<option value='{$r['Patient_ID']}'>{$r['Patient_ID']} - {$r['First_Name']} {$r['Last_Name']}</option>";
                  }
                ?>

              </select>
            </div>

            <div class="mb-3">
              <label for="edit_transaction_date" class="form-label">Transaction Date</label>
              <input type="date" name="Transaction_Date" id="edit_transaction_date" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="edit_payment_status" class="form-label">Payment Status</label>
              <select name="Payment_Status" id="edit_payment_status" class="form-control">
                <option>Paid</option>
                <option>Pending</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>

    //  Tab switching
    function switchTab(evt, tabName) {
      let i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
      }

      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
      }

      document.getElementById(tabName).classList.add("active");
      evt.currentTarget.classList.add("active");
    }

    //  Product check

    function checkProduct() {
      let pid = document.getElementById("product_id").value.trim();
      if (!pid) return alert("Enter Product ID");
      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          check_product: 1,
          product_id: pid
        })

      }).then(r => r.json()).then(data => {
        if (data.exists) {
          document.getElementById("product_name").value = data.name;
          document.getElementById("unit_price").value = data.unit_price;
          document.getElementById("quantity").value = 1;
          updateTotalPrice();

        } else {
          alert("Product not found");
          document.getElementById("product_name").value = '';
          document.getElementById("unit_price").value = '';
          document.getElementById("total_price").value = '';
        }
      });
    }

    //  Update total price with discount

    function updateTotalPrice() {
      let qty = parseFloat(document.getElementById("quantity").value) || 0;
      let price = parseFloat(document.getElementById("unit_price").value) || 0;
      let discount = parseFloat(document.getElementById("discount_percent").value) || 0;
      let total = qty * price * (1 - discount);
      document.getElementById("total_price").value = total.toFixed(2);
    }

    //  Items array

    let items = [];

    //  Add item to list

    function addItemToList() {
      let pid = document.getElementById("product_id").value.trim();
      let pname = document.getElementById("product_name").value.trim();
      let qty = parseInt(document.getElementById("quantity").value);
      let price = parseFloat(document.getElementById("unit_price").value);
      let total = parseFloat(document.getElementById("total_price").value);
      if (!pid || !pname || !qty || !price || !total) {
        return alert("Please fill all item fields correctly");
      }

      items.push({
        productID: pid,
        productName: pname,
        quantity: qty,
        unitPrice: price,
        totalPrice: total
      });

      updateTotalSales();
      resetItemForm();
    }

    //  Reset item form

    function resetItemForm() {
      document.getElementById("product_id").value = '';
      document.getElementById("product_name").value = '';
      document.getElementById("quantity").value = '';
      document.getElementById("unit_price").value = '';
      document.getElementById("total_price").value = '';
      document.getElementById("discount_percent").value = '0';
    }

    //  Update total sales display and hidden input

    function updateTotalSales() {
      let sum = items.reduce((a, c) => a + c.totalPrice, 0);
      document.getElementById("total_sales_display").textContent = `₱${sum.toFixed(2)}`;
      document.getElementById("total_amount").value = sum.toFixed(2);
      document.getElementById("item_list_data").value = JSON.stringify(items);
    }

    //  Submit transaction form

    document.getElementById("transactionForm").addEventListener("submit", function (e) {
      e.preventDefault();
      if (items.length === 0) {
        alert("Add at least one item before submitting.");
        return;
      }

      let formData = new FormData(this);
      formData.append("submit_transaction", 1);
      fetch('', {
        method: 'POST',
        body: formData

      }).then(r => r.json()).then(data => {
        if (data.success) {

          document.getElementById("successAlert").classList.remove("d-none");
          items = [];
          updateTotalSales();
          this.reset();
          resetItemForm();
        } else {
          alert("Failed to submit transaction");
        }
      });
    });

    //  Delete sale

    function deleteSale(id) {
      if (!confirm("Are you sure to delete this sale?")) return;
      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ delete_id: id })
      }).then(r => r.json()).then(data => {
        if (data.deleted) location.reload();
        else alert("Failed to delete");
      });
    }

    //  Edit sale modal handling

    let editModal = new bootstrap.Modal(document.getElementById('editSaleModal'));
    function editSale(id) {
      fetch(`?get_sale_data=1&sale_id=${id}`)
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.getElementById("edit_sale_id").value = data.sale.Sale_ID;
            document.getElementById("edit_patient_id").value = data.sale.Patient_ID;
            document.getElementById("edit_transaction_date").value = data.sale.Transaction_Date;
            document.getElementById("edit_payment_status").value = data.sale.Payment_Status;
            editModal.show();
          } else {
            alert("Failed to fetch sale data");
          }
        });
    }

    //  Handle edit sale form submission

    document.getElementById("editSaleForm").addEventListener("submit", function (e) {
      e.preventDefault();
      let formData = new FormData(this);
      formData.append("update_sale", 1);
      fetch('', {
        method: 'POST',
        body: formData
      }).then(r => r.json()).then(data => {
        if (data.success) {
          alert("Sale updated successfully");
          editModal.hide();
          location.reload();
        } else {
          alert("Update failed: " + (data.error ?? ''));
        }
      });
    });

  </script>
</body>
</html>
