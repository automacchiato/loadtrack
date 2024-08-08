<?php

   //Start session
   session_start();

   if (isset($_SESSION['employeeID'])){
      header("Location: indexAdmin.php");
      exit();
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
   <title>ECS Load Track Data</title>

   <link rel="stylesheet" type="text/css" href="vendor/bootstrap-4.6.1-dist/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap-table-master/dist/bootstrap-table.min.css">
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap-table-master/dist/extensions/filter-control/bootstrap-table-filter-control.css">
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap4.0/css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="assets/css/style.css">
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap-table-master/dist/extensions/sticky-header/bootstrap-table-sticky-header.css">
   <link rel="stylesheet" type="text/css" href="vendor/daterangepicker-master/daterangepicker.css">
   <link rel="stylesheet" href="vendor/x-editable-develop/dist/bootstrap3-editable/css/bootstrap-editable.css">
   <link rel="icon" type="image/svg" href="assets/img/diamond.svg"/>
   <link rel="stylesheet" type="text/css" href="vendor/Font-Awesome-master/css/all.css">
  
   <script src="vendor/bootstrap4.0/js/jquery-3.5.1.min.js"></script>
   <script src="vendor/daterangepicker-master/moment.min.js"></script>
   <script src="vendor/bootstrap4.0/js/cloudfare.js"></script>
   <script src="vendor/bootstrap4.0/js/bootstrap.js"></script>
   <script src="vendor/bootstrap4.0/js/bootstrap.bundle.js"></script>
   <script src="vendor/bootstrap-table-master/dist/bootstrap-table.min.js"></script>
   <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
   <script src="vendor/bootstrap-table-master/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
   <script src="vendor/bootstrap-table-master/dist/extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
   <script src="vendor/x-editable-develop/dist/bootstrap4-editable/js/bootstrap-editable.js"></script>
   <script src="vendor/bootstrap-table-master/dist/extensions/editable/bootstrap-table-editable.js"></script>
   <script src="assets/js/applyFilter.js"></script>
   <script src="assets/js/index.js"></script>

</head>
<body>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="imageModalDialog">
      <div class="modal-content" style="width: 1300px;">
        <div class="modal-body text-center">
          <img id="imageDisplay" src="#" style="width: auto; height: 70vh; margin-left: auto; margin-right: auto; display: block;">
        </div>
      </div>
    </div>
      <div>
        <p id="p1">Click anywhere to exit</p>
      </div>
  </div>

<!-- Side navbar -->
  <!-- <div id="mySidenav" class="sidenav" onclick="event.stopPropagation();">
    <div class="text-center container-fluid">
      <h4 style="color: azure;">Date Filter</h4>
      <input type="date"  id="myDatePicker" onchange="applyFilter()"/>
    </div><br><br>
    <div class="text-center container-fluid">
      <h4 style="color: azure;">Final Status</h4>
      <div class="outer">
         <div class="inner">
            <button class="btn btn-sm btn-primary" onclick="filterFinalStatus('unconfirmed')">Unconfirmed</button>
         </div>
         <div class="inner">
            <button class="btn btn-sm btn-success" onclick="filterFinalStatus('OK')">OK</button>
         </div>
         <div class="inner">
            <button class="btn btn-sm btn-danger"  onclick="filterFinalStatus('NG')">NG</button>
         </div>
         <div class="inner">
            <button class="btn btn-sm btn-warning"  onclick="filterFinalStatus('Need Adjustment')">Need Adjustment</button>
         </div>
      </div>
    </div>
  </div> -->

<!-- Start of Page -->
<div id="main" >

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">ECS Load Track Data</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <!-- <a class="nav-link active" href="#">Load Track Data</a> -->
      </li>
       <?php if ($_SESSION['employeeRole'] !== 'subadmin') { ?>
      <li class="nav-item">
        <!-- <a class="nav-link" href="admin_dashboard/index.php">Dashboard</a> -->
      </li>
      <?php } ?>
    </ul>
    <!-- <form class="form-inline my-2 my-lg-0"> -->
      <a class="nav-link text-white" href="adminLogin.php">
      <button class="btn btn-success btn-sm my-2 my-sm-0" style="margin-left: 10px;">Admin Login</button>
      </a>
    <!-- </form> -->
  </div>
</nav>

  <div class="bg-light sticky-top container-fluid">

    <!-- Toolbar -->
    <div id="toolbar" class="select" style="margin: 0;">
    <div class="row">
        <div class="col-sm">
          <input type="date"  id="myDatePicker" onchange="applyFilter()"/>
          <span><button type="button" class="btn btn-info pl-100" onclick="exportExcel()">Export to Excel</button></span>
        </div>
      </div>
      <!-- <span><button type="button" class="btn btn-lg btn-success fa fa-filter " id="btn_dateSelect"></button></span> -->
      <!-- <span><button type="button" class="btn btn-info" onclick="exportExcel()">Export to Excel</button></span> -->
    </div>

    <!-- Table -->
    <div class="">
      <table class="table table-light" id="myTable"
      data-pagination="true"
      data-pagination-v-align="top"
      data-toolbar="#toolbar"
      data-toggle="true"
      data-sortable="true"
      data-sticky-header="true"
      data-filter-control="true"
      data-id-field="id">
        <thead class="thead-light" >
          <tr>
            <th data-field="machine_no" data-filter-control="select" data-sortable="true">Machine Number</th>
            <th data-field="id_no" data-filter-control="select" data-sortable="true">Staff ID</th>
            <th data-field="checked_by" data-filter-control="select" data-sortable="true">Checked By</th>
            <th data-field="lotno" data-filter-control="select" data-sortable="true">Lot Number</th>
            <th data-field="filepath1" data-formatter="imageFormatter">Left Image</th>
            <th data-field="machine_status1" data-filter-control="select" data-sortable="true">Left Status</th>
            <th data-field="filepath2" data-formatter="imageFormatter">Right Image</th>
            <th data-field="machine_status2" data-filter-control="select" data-sortable="true">Right Status</th>
            <th data-field="textarea" data-sortable="true" data-escape="false" data-formatter="textFormatter">Comments</th>
            <th data-field="mt" data-sortable="true">Date & Time</th>
			<th data-field="ai_judge" data-sortable="true" data-filter-control="select">AI Judgement</th>
            <th data-field="confirmation" 
            data-editable-showbuttons="" 
            data-sortable="true"
            data-filter-control="select"
            data-editable=""
            data-editable-type="select" 
            data-editable-source="db/confirmation.php"
            data-editable-mode="inline"
            data-editable-url="db/post.php"
            data-editable-name="confirmation"
            data-editable-emptyText="Unconfirmed">Final Status</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
</body>
</html>


