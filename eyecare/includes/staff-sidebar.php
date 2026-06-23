
<!-- Sidebar -->
<aside class="sidebar">
  <head>
        <link rel="stylesheet" href="/eyecare/css/sidebar-design.css" />
  </head>

  <div class="sidebar-header">
    <img class="profileimg" src="/eyecaree/logos/account.svg" alt="account.svg">
    <h2 class="sidebar-title">PROFILE</h2>
  </div>

  <!-- Dashboard -->
  <div class="dropdown">
    <a href="staff_dashboard.php" class="dropdown-btn">
      <label for="dropdown0"><b>DASHBOARD</b></label>
    </a>
  </div>

  <!-- Inventory -->
  <div class="dropdown">
    <input type="checkbox" id="dropdown1">
    <label for="dropdown1" class="dropdown-btn"><b>INVENTORY</b></label>
    <div class="dropdown-content">
      <a href="staff-category.php" class="dropdown-item" style="color: white;">🗂️ Categories</a>
      <a href="staff-products.php" class="dropdown-item" style="color: white;">📦 Product</a>
    </div>
  </div>


  <!-- Point of Sale -->
  <div class="dropdown">
    <input type="checkbox" id="dropdown3">
    <label for="dropdown3" class="dropdown-btn"><b>POINT OF SALE</b></label>
    <div class="dropdown-content">
      <a href="staff-sales_transaction.php" class="dropdown-item" style="color: white;">💳 Sales Transaction</a>
    </div>
  </div>

  <!-- Logout -->
  <div class="out">
    <a href="login.php"><img class="logout" src="logos/back.svg" alt="back" title="logout"></a>
  </div>

</aside>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.dropdown input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
          checkboxes.forEach((other) => {
            if (other !== checkbox) {
              other.checked = false;
            }
          });
        }
      });
    });
  });
</script>
