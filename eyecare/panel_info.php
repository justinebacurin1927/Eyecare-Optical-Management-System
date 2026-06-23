<?php
$conn = new mysqli("localhost", "root", "", "optics_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// =====================
// Handle Delete
// =====================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_patient_id'])) {
    $id = intval($_POST['delete_patient_id']);
    $conn->query("DELETE FROM Patient WHERE Patient_ID = $id");
    echo "<script>window.location.href = window.location.pathname;</script>";
    exit();
}


// =====================
// Handle Edit (Update)
// =====================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_patient'])) {
    $id = intval($_POST['Patient_ID']);
    $first = $conn->real_escape_string($_POST['First_Name']);
    $middle = $conn->real_escape_string($_POST['Middle_Name']);
    $last = $conn->real_escape_string($_POST['Last_Name']);
    $gender = $conn->real_escape_string($_POST['Gender']);
    $address = $conn->real_escape_string($_POST['Address']);
    $birthdate = $conn->real_escape_string($_POST['Birthdate']);

    $conn->query("UPDATE Patient SET 
        First_Name = '$first',
        Middle_Name = '$middle',
        Last_Name = '$last',
        Gender = '$gender',
        Address = '$address',
        Birthdate = '$birthdate'
        WHERE Patient_ID = $id
    ");

    echo "<script>window.location.href = window.location.pathname;</script>";
    exit();
}


// =====================
// Handle Search Input
// =====================

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';


// =====================
// Pagination Setup
// =====================

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$count_sql = "
    SELECT COUNT(*) as total 
    FROM Patient p
    LEFT JOIN Prescription pr ON p.Patient_ID = pr.Patient_ID
    LEFT JOIN Lens_Type lt ON pr.Lens_Type_ID = lt.Lens_Type_ID
    LEFT JOIN Frame f ON pr.Frame_ID = f.Frame_ID
";

if ($search !== '') {
    $count_sql .= " WHERE 
        p.Patient_ID LIKE '%$search%' OR
        First_Name LIKE '%$search%' OR
        Middle_Name LIKE '%$search%' OR
        Last_Name LIKE '%$search%' OR
        Gender LIKE '%$search%' OR
        Address LIKE '%$search%' OR
        Phone_Number LIKE '%$search%' OR
        pr.Sphere LIKE '%$search%' OR
        pr.Cylinder LIKE '%$search%' OR
        pr.Axis LIKE '%$search%' OR
        pr.Addition LIKE '%$search%' OR
        pr.PD LIKE '%$search%' OR
        pr.Tint LIKE '%$search%' OR
        lt.Lens_Type_Name LIKE '%$search%' OR
        f.Frame_Name LIKE '%$search%'";
}

$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);


// =====================
// Main Query
// =====================

$sql = "
    SELECT p.Patient_ID, First_Name, Middle_Name, Last_Name, Birthdate, Gender, Phone_Number, Address,
           pr.Sphere, pr.Cylinder, pr.Axis, pr.Addition, pr.PD, lt.Lens_Type_Name, pr.Tint, f.Frame_Name
    FROM Patient p
    LEFT JOIN Prescription pr ON p.Patient_ID = pr.Patient_ID
    LEFT JOIN Lens_Type lt ON pr.Lens_Type_ID = lt.Lens_Type_ID
    LEFT JOIN Frame f ON pr.Frame_ID = f.Frame_ID
";

if ($search !== '') {
    $sql .= " WHERE 
        p.Patient_ID LIKE '%$search%' OR
        First_Name LIKE '%$search%' OR
        Middle_Name LIKE '%$search%' OR
        Last_Name LIKE '%$search%' OR
        Gender LIKE '%$search%' OR
        Address LIKE '%$search%' OR
        Phone_Number LIKE '%$search%' OR
        pr.Sphere LIKE '%$search%' OR
        pr.Cylinder LIKE '%$search%' OR
        pr.Axis LIKE '%$search%' OR
        pr.Addition LIKE '%$search%' OR
        pr.PD LIKE '%$search%' OR
        pr.Tint LIKE '%$search%' OR
        lt.Lens_Type_Name LIKE '%$search%' OR
        f.Frame_Name LIKE '%$search%'";
}

$sql .= " ORDER BY p.Patient_ID DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>See Patient Info Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css" />
  <link rel="stylesheet" href="/eyecaree/css/styles.css" />
  <link rel="stylesheet" href="/eyecaree/css/p-info.css" />
  <link rel="stylesheet" href="/eyecare/css/category.css" />
  <link rel="stylesheet" href="/eyecaree/css/bodylayout.css" />
  <style>
    @media print {
      body * { visibility: hidden; }
      .modal.show .modal-content, .modal.show .modal-content * {
        visibility: visible;
      }
      .modal.show .modal-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background-color: white !important;
      }
      .modal-footer { display: none !important; }
    }

     .button-sort {
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
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
  <div class="panel patient-info">
    <div class="headermenu-title">
      <img class="headerslogo" src="logos/patient info.svg" alt="patient info">
      <h3 class="headerstitle">See Patient Info Panel</h3>
    </div>
  </div>

  <form method="GET" class="button-row">
    <input type="text" name="search" class="search-form" placeholder="Search for ID or Name" value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn-search">Search</button>
    <a href="<?= basename($_SERVER['PHP_SELF']) ?>" class="button-show">Show All</a>
    <button type="button" class="button-sort" onclick="sortTable()">Sort by ID ↑↓</button>
  </form>

  <div class="panel patient-info">
    <form method="POST" id="actionForm">
      <input type="hidden" name="delete_patient_id" id="delete_patient_id">
    </form>

    <table id="patientTable" class="category-table table table-bordered">
      <thead>
        <tr>
          <th class="table-success">ID</th>
          <th class="table-success">Patient Name</th>
          <th class="table-success">Gender</th>
          <th class="table-success">Birthdate</th>
          <th class="table-success">Age</th>
          <th class="table-success">Address</th>
          <th class="table-success">Prescription Summary</th>
          <th class="table-success">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($patients as $row): 
          $patient_id = $row['Patient_ID'];
          $full_name = $row['First_Name'] . ' ' . $row['Middle_Name'] . ' ' . $row['Last_Name'];
          $birthdate = new DateTime($row['Birthdate']);
          $age = $birthdate->diff(new DateTime())->y;
          $prescription = isset($row['Sphere']) ? 
            "Sphere: {$row['Sphere']}, Cylinder: {$row['Cylinder']}, Axis: {$row['Axis']}, Add: {$row['Addition']}, PD: {$row['PD']}, Lens: {$row['Lens_Type_Name']}, Tint: {$row['Tint']}, Frame: {$row['Frame_Name']}" 
            : 'No prescription';
        ?>
        <tr>
          <td><?= $patient_id ?></td>
          <td><?= htmlspecialchars($full_name) ?></td>
          <td><?= htmlspecialchars($row['Gender']) ?></td>
          <td><?= htmlspecialchars($row['Birthdate']) ?></td>
          <td><?= $age ?></td>
          <td><?= htmlspecialchars($row['Address']) ?></td>
          <td><?= htmlspecialchars($prescription) ?></td>
          <td>
            <div class="d-flex gap-1 justify-content-center">
              <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#seeModal<?= $patient_id ?>">VIEW</button>
              <button class="btn btn-primary btn-sm" type="button" onclick="editPatient(<?= $patient_id ?>)">EDIT</button>
              <button class="btn btn-danger btn-sm" type="button" onclick="deletePatient(<?= $patient_id ?>)">DELETE</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagination Buttons -->
    <div class="d-flex justify-content-center mt-4">
      <nav>
        <ul class="pagination">
          <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a></li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a></li>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>

    <!-- Modals for Edit and See -->
    <?php foreach ($patients as $row): 
      $patient_id = $row['Patient_ID'];
      $full_name = $row['First_Name'] . ' ' . $row['Middle_Name'] . ' ' . $row['Last_Name'];
      $birthdate = new DateTime($row['Birthdate']);
      $age = $birthdate->diff(new DateTime())->y;
    ?>
   <!-- Edit Modal -->
<div class='modal fade' id='editModal<?= $patient_id ?>' tabindex='-1' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <form method='POST'>
        <div class='modal-header'>
          <h5 class='modal-title'>Edit Patient</h5>
          <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
        </div>
        <div class='modal-body'>
          <input type='hidden' name='Patient_ID' value='<?= $patient_id ?>'>
          <input type='hidden' name='update_patient' value='1'>

          <div class='mb-3'>
            <label class='form-label'>First Name</label>
            <input type='text' class='form-control' name='First_Name' value='<?= $row['First_Name'] ?>' required>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Middle Name</label>
            <input type='text' class='form-control' name='Middle_Name' value='<?= $row['Middle_Name'] ?>' required>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Last Name</label>
            <input type='text' class='form-control' name='Last_Name' value='<?= $row['Last_Name'] ?>' required>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Gender</label>
            <select class='form-select' name='Gender'>
              <option value='Male' <?= ($row['Gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
              <option value='Female' <?= ($row['Gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
            </select>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Birthdate</label>
            <input type='date' class='form-control' name='Birthdate' value='<?= $row['Birthdate'] ?>' required>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Address</label>
            <textarea class='form-control' name='Address' required><?= $row['Address'] ?></textarea>
          </div>

          <hr class="my-3">
          <h6 class="text-primary">Prescription Info</h6>

          <div class='mb-3'>
            <label class='form-label'>Sphere</label>
            <input type='text' class='form-control' name='Sphere' value='<?= htmlspecialchars($row['Sphere'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Cylinder</label>
            <input type='text' class='form-control' name='Cylinder' value='<?= htmlspecialchars($row['Cylinder'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Axis</label>
            <input type='text' class='form-control' name='Axis' value='<?= htmlspecialchars($row['Axis'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Addition</label>
            <input type='text' class='form-control' name='Addition' value='<?= htmlspecialchars($row['Addition'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>PD</label>
            <input type='text' class='form-control' name='PD' value='<?= htmlspecialchars($row['PD'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Tint</label>
            <input type='text' class='form-control' name='Tint' value='<?= htmlspecialchars($row['Tint'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Lens Type</label>
            <input type='text' class='form-control' name='Lens_Type_Name' value='<?= htmlspecialchars($row['Lens_Type_Name'] ?? '') ?>'>
          </div>
          <div class='mb-3'>
            <label class='form-label'>Frame</label>
            <input type='text' class='form-control' name='Frame_Name' value='<?= htmlspecialchars($row['Frame_Name'] ?? '') ?>'>
          </div>
        </div>

        <div class='modal-footer'>
          <button type='submit' class='btn btn-primary'>Save changes</button>
          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>


    <!-- See Modal -->
    <div class='modal fade' id='seeModal<?= $patient_id ?>' tabindex='-1' aria-hidden='true'>
      <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title'>Patient Details</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
          </div>
          <div class='modal-body'>
            <table class='table table-bordered'>
              <tr><th>ID</th><td><?= $patient_id ?></td></tr>
              <tr><th>Name</th><td><?= htmlspecialchars($full_name) ?></td></tr>
              <tr><th>Gender</th><td><?= htmlspecialchars($row['Gender']) ?></td></tr>
              <tr><th>Birthdate</th><td><?= $row['Birthdate'] ?> (<?= $age ?> yrs old)</td></tr>
              <tr><th>Address</th><td><?= htmlspecialchars($row['Address']) ?></td></tr>
              <tr><th>Phone</th><td><?= htmlspecialchars($row['Phone_Number']) ?></td></tr>
              <tr><th colspan='2' class='text-center bg-light'>Prescription Details</th></tr>
              <?php if (isset($row['Sphere'])): ?>
                <tr><th>Sphere</th><td><?= $row['Sphere'] ?></td></tr>
                <tr><th>Cylinder</th><td><?= $row['Cylinder'] ?></td></tr>
                <tr><th>Axis</th><td><?= $row['Axis'] ?></td></tr>
                <tr><th>Addition</th><td><?= $row['Addition'] ?></td></tr>
                <tr><th>PD</th><td><?= $row['PD'] ?></td></tr>
                <tr><th>Lens</th><td><?= $row['Lens_Type_Name'] ?></td></tr>
                <tr><th>Tint</th><td><?= $row['Tint'] ?></td></tr>
                <tr><th>Frame</th><td><?= $row['Frame_Name'] ?></td></tr>
              <?php else: ?>
                <tr><td colspan='2'>No prescription info available.</td></tr>
              <?php endif; ?>
            </table>
            <div class='text-end'>
              <button class='btn btn-outline-secondary print-btn' onclick='printModal(this)'>🖨️ Print</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function sortTable() {
  const table = document.getElementById('patientTable');
  const rows = Array.from(table.rows).slice(1);
  const sorted = rows.sort((a, b) => parseInt(a.cells[0].innerText) - parseInt(b.cells[0].innerText));
  const tbody = table.querySelector('tbody');
  tbody.innerHTML = '';
  sorted.forEach(row => tbody.appendChild(row));
}

function deletePatient(id) {
  if (confirm('Are you sure you want to delete this patient?')) {
    document.getElementById('delete_patient_id').value = id;
    document.getElementById('actionForm').submit();
  }
}

function editPatient(id) {
  const modal = new bootstrap.Modal(document.getElementById('editModal' + id));
  modal.show();
}

function printModal(btn) {
  const modalContent = btn.closest('.modal-content');
  const printWindow = window.open('', '', 'width=900,height=700');
  printWindow.document.write('<html><head><title>Print</title>');
  printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
  printWindow.document.write('</head><body>');
  printWindow.document.write(modalContent.innerHTML);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
  printWindow.close();
}
</script>
</body>
</html>
