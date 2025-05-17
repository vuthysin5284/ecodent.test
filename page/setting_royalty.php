<?php
  $page = 'Setting Royalty';
  include_once('../inc/session.php');
  include_once('../inc/config.php');
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <?php 
      if ($lang == 1) {
        $page_category = 'Setting';
        $page_title = 'Setting Royalty';
        $text_btn_create = 'New';
        $text_form = 'Setting Royalty';
        $m1 = 'Method ID';
        $m2 = 'Setting Royalty';
        $th2 = 'Image';
        $th3 = 'Setting Royalty';
        $th4 = 'Action';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'ព័ត៌មានរបស់ប្រព័ន្ធ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ព័ត៌មានរបស់ប្រព័ន្ធ';
        $m1 = 'លេខកូដ';
        $m2 = 'វិធីបង់ប្រាក់';
        $th2 = 'រូបភាព';
        $th3 = 'វិធីបង់ប្រាក់';
        $th4 = 'ដំណើរការ';
      }
      include_once('../inc/header.php');
      include_once('../inc/setting.php');

      // loading data
      $row = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
    ?>  
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div> 
              </div>
              
              <?php include_once ('../inc/royalty_menu.php'); ?>
 
 
              <div class="card mb-4">
                <div class="card-body"> 
                  <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <div class="row g-5">
                    <body class="bg-gray-100">
  <!-- Navigation Bar -->
  <nav class="bg-blue-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-xl font-bold">Royalty & Commission Interface</h1>
      <div>
        <button class="tab-btn tab-active mr-4" data-tab="dashboard">Dashboard</button>
        <button class="tab-btn mr-4" data-tab="agreements">Agreements</button>
        <button class="tab-btn mr-4" data-tab="sales">Sales/Revenue</button>
        <button class="tab-btn mr-4" data-tab="calculations">Calculations</button>
        <button class="tab-btn mr-4" data-tab="payments">Payments</button>
        <button class="tab-btn" data-tab="payee">Payee Portal</button>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mx-auto p-6">
    <!-- Dashboard -->
    <div id="dashboard" class="tab-content">
      <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold">Total Revenue</h3>
          <p class="text-2xl" id="total-revenue">$0.00</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold">Amounts Owed</h3>
          <p class="text-2xl" id="amounts-owed">$0.00</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-lg font-semibold">Payments Pending</h3>
          <p class="text-2xl" id="payments-pending">0</p>
        </div>
      </div>
      <div class="mt-6">
        <h3 class="text-lg font-semibold">Royalties & Commissions by Payee</h3>
        <div class="bg-white p-4 rounded shadow">
          <p>Chart placeholder (requires Chart.js for visualization)</p>
        </div>
      </div>
    </div>

    <!-- Agreements -->
    <div id="agreements" class="tab-content hidden">
      <h2 class="text-2xl font-bold mb-4">Agreements</h2>
      <form id="agreement-form" class="mb-6 bg-white p-4 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input type="text" id="agreement-payee" placeholder="Payee Name" class="p-2 border rounded" required>
          <select id="agreement-type" class="p-2 border rounded" required>
            <option value="Royalty">Royalty</option>
            <option value="Commission">Commission</option>
          </select>
          <input type="text" id="agreement-rate" placeholder="Rate (e.g., 20% or $0.01 or tiered)" class="p-2 border rounded" required>
          <input type="number" id="agreement-advance" placeholder="Advance" class="p-2 border rounded">
          <input type="number" id="agreement-recoupment" placeholder="Recoupment Balance" class="p-2 border rounded">
          <input type="text" id="agreement-territory" placeholder="Territory" class="p-2 border rounded">
        </div>
        <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Add Agreement</button>
      </form>
      <div class="table-container">
        <table class="w-full bg-white rounded shadow" id="agreement-table">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">Payee</th>
              <th class="p-2">Type</th>
              <th class="p-2">Rate</th>
              <th class="p-2">Advance</th>
              <th class="p-2">Recoupment</th>
              <th class="p-2">Territory</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- Sales/Revenue -->
    <div id="sales" class="tab-content hidden">
      <h2 class="text-2xl font-bold mb-4">Sales/Revenue</h2>
      <form id="sales-form" class="mb-6 bg-white p-4 rounded shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input type="date" id="sales-date" class="p-2 border rounded" required>
          <input type="text" id="sales-source" placeholder="Source (e.g., Spotify, CRM)" class="p-2 border rounded" required>
          <input type="text" id="sales-product" placeholder="Product/Deal" class="p-2 border rounded" required>
          <input type="number" id="sales-units" placeholder="Units" class="p-2 border rounded" required>
          <input type="number" id="sales-revenue" placeholder="Revenue" class="p-2 border rounded" required>
        </div>
        <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Add Sale</button>
      </form>
      <div class="table-container">
        <table class="w-full bg-white rounded shadow" id="sales-table">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">Date</th>
              <th class="p-2">Source</th>
              <th class="p-2">Product/Deal</th>
              <th class="p-2">Units</th>
              <th class="p-2">Revenue</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- Calculations -->
    <div id="calculations" class="tab-content hidden">
      <h2 class="text-2xl font-bold mb-4">Royalty & Commission Calculations</h2>
      <div class="table-container">
        <table class="w-full bg-white rounded shadow" id="calculation-table">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">Payee</th>
              <th class="p-2">Product/Deal</th>
              <th class="p-2">Revenue</th>
              <th class="p-2">Rate</th>
              <th class="p-2">Amount Owed</th>
              <th class="p-2">Deductions</th>
              <th class="p-2">Net Payable</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- Payments -->
    <div id="payments" class="tab-content hidden">
      <h2 class="text-2xl font-bold mb-4">Payments</h2>
      <div class="table-container">
        <table class="w-full bg-white rounded shadow" id="payment-table">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-2">Payee</th>
              <th class="p-2">Date</th>
              <th class="p-2">Amount</th>
              <th class="p-2">Method</th>
              <th class="p-2">Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- Payee Portal -->
    <div id="payee" class="tab-content hidden">
      <h2 class="text-2xl font-bold mb-4">Payee Portal</h2>
      <div class="bg-white p-4 rounded shadow">
        <h3 class="text-lg font-semibold">Your Statements</h3>
        <p class="mt-2">View your royalty and commission statements below.</p>
        <div class="table-container mt-4">
          <table class="w-full" id="payee-table">
            <thead>
              <tr class="bg-gray-200">
                <th class="p-2">Period</th>
                <th class="p-2">Type</th>
                <th class="p-2">Amount Owed</th>
                <th class="p-2">Net Paid</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="p-2">Q2 2025</td>
                <td class="p-2">Royalty</td>
                <td class="p-2">$10.00</td>
                <td class="p-2">$0.00</td>
              </tr>
              <tr>
                <td class="p-2">Q2 2025</td>
                <td class="p-2">Commission</td>
                <td class="p-2">$1,700.00</td>
                <td class="p-2">$1,700.00</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sample data
    let agreements = [
      { id: 'A001', payee: 'Artist A', type: 'Royalty', rate: '20%', advance: 5000, recoupment: 3000, territory: 'Global' },
      { id: 'A002', payee: 'Agent B', type: 'Commission', rate: '10% up to $10K, 12% above', advance: 0, recoupment: 0, territory: 'US' }
    ];

    let sales = [
      { date: '2025-04-01', source: 'Spotify', product: 'Song X', units: 10000, revenue: 50 },
      { date: '2025-04-01', source: 'CRM', product: 'Sale Y', units: 1, revenue: 15000 }
    ];

    let payments = [
      { payee: 'Artist A', date: '2025-04-15', amount: 0, method: 'Bank Transfer', status: 'Pending' },
      { payee: 'Agent B', date: '2025-04-15', amount: 1700, method: 'PayPal', status: 'Completed' }
    ];

    // Initialize the interface
    $(document).ready(function() {
      // Tab navigation
      $('.tab-btn').click(function() {
        $('.tab-btn').removeClass('tab-active');
        $(this).addClass('tab-active');
        $('.tab-content').addClass('hidden');
        $(`#${$(this).data('tab')}`).removeClass('hidden');
      });

      // Render initial data
      renderAgreements();
      renderSales();
      renderCalculations();
      renderPayments();
      updateDashboard();

      // Handle agreement form submission
      $('#agreement-form').submit(function(e) {
        e.preventDefault();
        const newAgreement = {
          id: `A${agreements.length + 1}`,
          payee: $('#agreement-payee').val(),
          type: $('#agreement-type').val(),
          rate: $('#agreement-rate').val(),
          advance: parseFloat($('#agreement-advance').val()) || 0,
          recoupment: parseFloat($('#agreement-recoupment').val()) || 0,
          territory: $('#agreement-territory').val()
        };
        agreements.push(newAgreement);
        renderAgreements();
        renderCalculations();
        updateDashboard();
        $('#agreement-form')[0].reset();
      });

      // Handle sales form submission
      $('#sales-form').submit(function(e) {
        e.preventDefault();
        const newSale = {
          date: $('#sales-date').val(),
          source: $('#sales-source').val(),
          product: $('#sales-product').val(),
          units: parseFloat($('#sales-units').val()),
          revenue: parseFloat($('#sales-revenue').val())
        };
        sales.push(newSale);
        renderSales();
        renderCalculations();
        updateDashboard();
        $('#sales-form')[0].reset();
      });
    });

    // Render agreements table
    function renderAgreements() {
      const tbody = $('#agreement-table tbody').empty();
      agreements.forEach(a => {
        tbody.append(`
          <tr>
            <td class="p-2">${a.payee}</td>
            <td class="p-2">${a.type}</td>
            <td class="p-2">${a.rate}</td>
            <td class="p-2">$${a.advance.toFixed(2)}</td>
            <td class="p-2">$${a.recoupment.toFixed(2)}</td>
            <td class="p-2">${a.territory}</td>
          </tr>
        `);
      });
    }

    // Render sales table
    function renderSales() {
      const tbody = $('#sales-table tbody').empty();
      sales.forEach(s => {
        tbody.append(`
          <tr>
            <td class="p-2">${s.date}</td>
            <td class="p-2">${s.source}</td>
            <td class="p-2">${s.product}</td>
            <td class="p-2">${s.units}</td>
            <td class="p-2">$${s.revenue.toFixed(2)}</td>
          </tr>
        `);
      });
    }

    // Render calculations table
    function renderCalculations() {
      const tbody = $('#calculation-table tbody').empty();
      const calculations = calculateAmounts();
      calculations.forEach(c => {
        tbody.append(`
          <tr>
            <td class="p-2">${c.payee}</td>
            <td class="p-2">${c.product}</td>
            <td class="p-2">$${c.revenue.toFixed(2)}</td>
            <td class="p-2">${c.rate}</td>
            <td class="p-2">$${c.amountOwed.toFixed(2)}</td>
            <td class="p-2">$${c.deductions.toFixed(2)}</td>
            <td class="p-2">$${c.netPayable.toFixed(2)}</td>
          </tr>
        `);
      });
    }

    // Render payments table
    function renderPayments() {
      const tbody = $('#payment-table tbody').empty();
      payments.forEach(p => {
        tbody.append(`
          <tr>
            <td class="p-2">${p.payee}</td>
            <td class="p-2">${p.date}</td>
            <td class="p-2">$${p.amount.toFixed(2)}</td>
            <td class="p-2">${p.method}</td>
            <td class="p-2">${p.status}</td>
          </tr>
        `);
      });
    }

    // Calculate royalties and commissions
    function calculateAmounts() {
      return sales.map(sale => {
        // Match payee based on product (simplified for demo)
        const agreement = agreements.find(a => 
          (sale.product === 'Song X' && a.payee === 'Artist A') || 
          (sale.product === 'Sale Y' && a.payee === 'Agent B')
        );
        if (!agreement) return null;

        const revenue = parseFloat(sale.revenue);
        const units = parseFloat(sale.units);
        let amountOwed = 0;
        let rateDisplay = agreement.rate;

        // Handle royalty or commission calculation
        if (agreement.type === 'Royalty') {
          const rate = agreement.rate.includes('%') ? parseFloat(agreement.rate) / 100 : parseFloat(agreement.rate);
          amountOwed = agreement.rate.includes('%') ? revenue * rate : units * rate;
        } else if (agreement.type === 'Commission') {
          // Handle tiered commission (e.g., 10% up to $10K, 12% above)
          if (agreement.rate.includes('tiered')) {
            rateDisplay = '10% up to $10K, 12% above';
            amountOwed = revenue <= 10000 ? revenue * 0.10 : (10000 * 0.10) + ((revenue - 10000) * 0.12);
          } else {
            const rate = parseFloat(agreement.rate) / 100;
            amountOwed = revenue * rate;
          }
        }

        const deductions = Math.min(parseFloat(agreement.recoupment), amountOwed);
        const netPayable = amountOwed - deductions;

        return {
          payee: agreement.payee,
          product: sale.product,
          revenue,
          rate: rateDisplay,
          amountOwed,
          deductions,
          netPayable
        };
      }).filter(c => c !== null);
    }

    // Update dashboard metrics
    function updateDashboard() {
      const totalRevenue = sales.reduce((sum, s) => sum + parseFloat(s.revenue), 0);
      const amountsOwed = calculateAmounts().reduce((sum, c) => sum + c.amountOwed, 0);
      const paymentsPending = payments.filter(p => p.status === 'Pending').length;

      $('#total-revenue').text(`$${totalRevenue.toFixed(2)}`);
      $('#amounts-owed').text(`$${amountsOwed.toFixed(2)}`);
      $('#payments-pending').text(paymentsPending);
    }
  </script>
</body>
                    </div> 
                  </div>
                </div> 
              </div>
            </div>
            <?php include_once('../inc/footage.php'); ?>
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <script src="../script/page_main.js"></script>
    <script src="../script/page_payment_method.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("System Info");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
