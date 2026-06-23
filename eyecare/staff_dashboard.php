<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "optics_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

  
// Summary counts
$total_products = $conn->query("SELECT COUNT(*) AS total FROM product")->fetch_assoc()['total'];
$total_patients = $conn->query("SELECT COUNT(*) AS total FROM patient")->fetch_assoc()['total'];
$total_sales = $conn->query("SELECT SUM(Total_Amount) AS total FROM sale_transaction")->fetch_assoc()['total'] ?? 0;
$total_users = $conn->query("SELECT COUNT(*) AS total FROM user_account")->fetch_assoc()['total'];

  
// Data for charts
$category_data = $conn->query("
  SELECT cat.Category_Name, COUNT(p.Product_ID) AS ProductCount
  FROM categories cat
  LEFT JOIN product p ON p.Category_ID = cat.Category_ID
  GROUP BY cat.Category_Name
");

  
$monthly_sales = $conn->query("
  SELECT DATE_FORMAT(Transaction_Date, '%Y-%m') AS Month, SUM(Total_Amount) AS Total
  FROM sale_transaction
  GROUP BY Month ORDER BY Month
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/eyecaree/includes/sidebar-design.css">
  <link rel="stylesheet" href="/eyecaree/css/category.css">
  <style>
    body {
      margin: 0;
      display: flex;
      font-family: Arial, sans-serif;
      background: #ecf0f1;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      flex: 1;
    }

    .dashboard-header h3 {
      color: #2c3e50;
      margin-bottom: 20px;
    }

    .summary-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }

    .card-summary {
      background: white;
      border: 8px solid #2c3e50;
      color: #2c3e50;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      flex: 1 1 200px;
      font-weight: bold;
      font-size: 16px;
    }

    .charts-section {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
    }

    .chart-box {
      flex: 1 1 45%;
      background: white;
      border: 8px solid #2c3e50;
      color: #2c3e50;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    canvas {
      width: 100% !important;
      height: 350px !important;
    }

    .chart-box h5 {
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<?php include 'includes/staff-sidebar.php'; ?>

<div class="main-content">
  <div class="panel panel-headers">
    <div class="headermenu-title">
      <h3 class="headerstitle">Welcome to the Dashboard</h3>
    </div>
  </div>

  
  <!-- Summary Cards -->
  <div class="summary-cards">
    <div class="card-summary">Products<br><?= $total_products ?></div>
    <div class="card-summary">Patients<br><?= $total_patients ?></div>
    <div class="card-summary">Total Sales<br>₱<?= number_format($total_sales, 2) ?></div>
    <div class="card-summary">Users<br><?= $total_users ?></div>
  </div>

  
  <!-- Charts -->
  <div class="charts-section">
    <div class="chart-box">
      <h5>Products by Category</h5>
      <canvas id="categoryPie"></canvas>
    </div>
    <div class="chart-box">
      <h5>Monthly Sales Trend</h5>
      <canvas id="salesBar"></canvas>
    </div>
  </div>
</div>

  
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const categoryLabels = <?= json_encode(array_column(iterator_to_array($category_data), 'Category_Name')) ?>;
const categoryCounts = <?= json_encode(array_column(iterator_to_array($category_data), 'ProductCount')) ?>;
const monthlyLabels = <?= json_encode(array_column(iterator_to_array($monthly_sales), 'Month')) ?>;
const monthlyTotals = <?= json_encode(array_column(iterator_to_array($monthly_sales), 'Total')) ?>;

new Chart(document.getElementById('categoryPie'), {
  type: 'pie',
  data: {
    labels: categoryLabels,
    datasets: [{
      label: 'Products per Category',
      data: categoryCounts,
      backgroundColor: ['#85abf2','#f28e2c','#e15759','#76b7b2','#59a14f','#edc949']
    }]
  }
});

new Chart(document.getElementById('salesBar'), {
  type: 'bar',
  data: {
    labels: monthlyLabels,
    datasets: [{
      label: 'Monthly Sales',
      data: monthlyTotals,
      backgroundColor: '#3498db'
    }]
  },
  options: {
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>

</body>
</html>

<?php $conn->close(); ?>
