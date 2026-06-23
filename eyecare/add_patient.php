<?php
// Establish Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "optics_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


// Fetch available lenses and frames from the database for dropdown options
$lenses = $conn->query("SELECT * FROM lens_type");
$frames = $conn->query("SELECT * FROM frame");


// Initialize modal message and visibility
$modalMessage = "";
$showModal = false;


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve patient input values
    $firstname   = $_POST['FIRSTNAME'] ?? '';
    $middlename  = $_POST['MIDDLENAME'] ?? '';
    $surname     = $_POST['SURNAME'] ?? '';
    $address     = $_POST['ADDRESS'] ?? '';
    $birthdate   = $_POST['BIRTHDATE'] ?? '';
    $gender      = $_POST['GENDER'] ?? '';
    $phone       = $_POST['PHONE'] ?? '';


    // Retrieve prescription input values
    $sphere      = $_POST['SPHERE'] ?? '';
    $cylinder    = $_POST['CYLINDER'] ?? '';
    $axis        = $_POST['AXIS'] ?? '';
    $addition    = $_POST['ADDITION'] ?? '';
    $pd          = $_POST['PD'] ?? '';
    $lens        = $_POST['LENS'] ?? '';
    $tint        = $_POST['TINT'] ?? '';
    $frame       = $_POST['FRAME'] ?? '';
    $prescribed  = $_POST['PRESCRIBED'] ?? '';


    // Check for required fields
    if (empty($firstname) || empty($surname) || empty($sphere) || empty($cylinder) || empty($axis)) {
        $modalMessage = "Please fill in all required fields!";
        $showModal = true;
    } else {

        // Insert into Patient table
        $sql_patient = "INSERT INTO Patient (First_Name, Middle_Name, Last_Name, Birthdate, Gender, Phone_Number, Address) 
                        VALUES ('$firstname', '$middlename', '$surname', '$birthdate', '$gender', '$phone', '$address')";


        if ($conn->query($sql_patient) === TRUE) {
            $patient_id = $conn->insert_id;


            // Insert into Prescription table
            $sql_prescription = "INSERT INTO Prescription 
                (Patient_ID, Sphere, Cylinder, Axis, Addition, PD, Lens_Type_ID, Tint, Frame_ID, Prescribed) 
                VALUES ('$patient_id', '$sphere', '$cylinder', '$axis', '$addition', '$pd', '$lens', '$tint', '$frame', '$prescribed')";


            if ($conn->query($sql_prescription) === TRUE) {
                $modalMessage = "Patient and Prescription added successfully.";
            } else {
                $modalMessage = "Error inserting prescription: " . $conn->error;
            }

        } else {
            $modalMessage = "Error inserting patient: " . $conn->error;
        }

        $showModal = true;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Patient Panel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css" />
  <link rel="stylesheet" href="/eyecaree/css/styles.css" />
  <link rel="stylesheet" href="/eyecaree/css/add-patient.css" />
  <link rel="stylesheet" href="/eyecaree/css/category.css" />
  <link rel="stylesheet" href="/eyecaree/css/bodylayout.css" />
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

  <div class="panel-headers">
    <div class="headermenu-title">
      <img class="headerslogo" src="logos/patient.svg" alt="patient">
      <h3 class="headerstitle">Add Patient Panel</h3>
    </div>
  </div>


  <!-- Patient Information Form -->
  <div class="panel panel-categories">
    <form action="add_patient.php" method="POST">
      <div class="container">
        <div class="row g-3">

          <div class="col-md-3">
            <label for="FIRSTNAME" class="form-label text-white">First Name</label>
            <input type="text" id="FIRSTNAME" name="FIRSTNAME" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label for="MIDDLENAME" class="form-label text-white">Middle Name</label>
            <input type="text" id="MIDDLENAME" name="MIDDLENAME" class="form-control">
          </div>

          <div class="col-md-3">
            <label for="SURNAME" class="form-label text-white">Surname</label>
            <input type="text" id="SURNAME" name="SURNAME" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label class="form-label text-white">Age</label>
            <input type="text" id="AGE" class="form-control" readonly>
          </div>

          <div class="col-md-3">
            <label for="ADDRESS" class="form-label text-white">Address</label>
            <input type="text" id="ADDRESS" name="ADDRESS" class="form-control" required>
          </div>

          <div class="col-md-3">
            <label for="BIRTHDATE" class="form-label text-white">Birthdate</label>
            <input type="date" id="BIRTHDATE" name="BIRTHDATE" class="form-control" required onchange="calculateAge()">
          </div>

          <div class="col-md-3">
            <label for="GENDER" class="form-label text-white">Gender</label>
            <select id="GENDER" name="GENDER" class="form-select" required>
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Others">Others</option>
            </select>
          </div>

          <div class="col-md-3">
            <label for="PHONE" class="form-label text-white">Phone Number</label>
            <input type="tel" id="PHONE" name="PHONE" class="form-control" required>
          </div>

        </div>
      </div>
  </div>


  <!-- Prescription Information Form -->
  <div class="panel panel-categories mt-4">
    <h3 class="headerstitle">Prescription Details</h3>
    <br><br>

    <div class="container">
      <div class="row g-3">

        <div class="col-md-3">
          <label for="SPHERE" class="form-label text-white">Sphere</label>
          <input type="text" id="SPHERE" name="SPHERE" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="CYLINDER" class="form-label text-white">Cylinder</label>
          <input type="text" id="CYLINDER" name="CYLINDER" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="AXIS" class="form-label text-white">Axis</label>
          <input type="text" id="AXIS" name="AXIS" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="ADDITION" class="form-label text-white">Addition</label>
          <input type="text" id="ADDITION" name="ADDITION" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="PD" class="form-label text-white">PD</label>
          <input type="text" id="PD" name="PD" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="LENS" class="form-label text-white">Lens</label>
          <select id="LENS" name="LENS" class="form-select" required>
            <option value="">Select Lens</option>
            <?php if ($lenses): while ($lens = $lenses->fetch_assoc()): ?>
              <option value="<?= $lens['Lens_Type_ID'] ?>"><?= htmlspecialchars($lens['Lens_Type_Name']) ?></option>
            <?php endwhile; endif; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="TINT" class="form-label text-white">Tint</label>
          <input type="text" id="TINT" name="TINT" class="form-control" required>
        </div>

        <div class="col-md-3">
          <label for="FRAME" class="form-label text-white">Frame</label>
          <select id="FRAME" name="FRAME" class="form-select" required>
            <option value="">Select Frame</option>
            <?php if ($frames): while ($frame = $frames->fetch_assoc()): ?>
              <option value="<?= $frame['Frame_ID'] ?>"><?= htmlspecialchars($frame['Frame_Name']) ?></option>
            <?php endwhile; endif; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="PRESCRIBED" class="form-label text-white">Prescribed By</label>
          <input type="text" id="PRESCRIBED" name="PRESCRIBED" class="form-control">
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary mt-3 px-4">SUBMIT</button>
        </div>

      </div>
    </div>
  </div>
</form>
</div>


<!-- Modal for displaying result messages -->
<?php if ($showModal): ?>
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Submission Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= htmlspecialchars($modalMessage) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>


<!-- Calculate Age Script -->
<script>
function calculateAge() {
  const birthdate = document.getElementById('BIRTHDATE').value;
  const ageField = document.getElementById('AGE');

  if (birthdate) {
    const today = new Date();
    const birthDate = new Date(birthdate);
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }

    ageField.value = age;
  } else {
    ageField.value = '';
  }
}
</script>

<!-- Bootstrap Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Auto-show modal on submission -->
<?php if ($showModal): ?>
<script>
  var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
  feedbackModal.show();
</script>
<?php endif; ?>
</body>
</html>
