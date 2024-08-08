<?php 

session_start();
if (!isset($_SESSION['employeeID'])){
   header("Location: ../adminLogin.php");
   exit();
} else {
   if ($_SESSION['employeeRole'] == 'subadmin') {
      header("location: ../index.php");
   }
}

include('server.php');

// edit function
if (isset($_GET['edit'])) {
   $id = $_GET['edit'];
   $update = true;
   $record = mysqli_query($db, "SELECT * FROM loadtrack_admins WHERE ID=$id");

   if ($record != 0) {
      $n = mysqli_fetch_array($record);
      $employeeID = $n['employeeID'];
      $employeeName = $n['employeeName'];
      $employeeRole = $n['employeeRole'];
      $employeePwd = $n['employeePwd'];
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ECS LTD - Admin Dashboard</title>

   <link href="../vendor/bootstrap-4.6.1-dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="../vendor/Font-Awesome-master/css/all.css">
   <link rel="stylesheet" href="style.css">
   <link rel="icon" type="image/png" href="../assets/img/admin.png"/>
   <link rel="stylesheet" href="../vendor/sweetalert2/sweetalert2.min.css">

   <script src="../vendor/jquery-datatables/jQuery-3.6.0/jquery-3.6.0.min.js"></script>
   <script src="../vendor/bootstrap4.0/js/bootstrap.js"></script>
   <script src="../vendor/sweetalert2/sweetalert2.all.min.js"></script>
   <script src="../assets/js/sweetalert.js"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">ECS Load Track Data</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="../indexAdmin.php">Load Track Data</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="#">Dashboard</a>
      </li>
    </ul>
    <!-- <form class="form-inline my-2 my-lg-0"> -->
      <a class="nav-link text-white" href="../adminLogout.php">Hi, <?php echo strtok($_SESSION['employeeName'],""); ?>
      <button class="btn btn-danger btn-sm my-2 my-sm-0" style="margin-left: 10px;">Log Out</button>
      </a>
    <!-- </form> -->
  </div>
</nav>

   <div class="container-fluid">

   <!-- <div class="wrapper text-center">
    <h4 class="card-title">Alerts Popups</h4>
    <p class="card-description">A message with auto close timer</p> <button class="btn btn-outline-success" onclick="showSwal('auto-close')">Click here!</button>
</div> -->

   <div class="row">
      <div class="col-sm-6 pt-3">
         <?php 
            if ($_SESSION['employeeRole'] == 'admin') { ?>
         <h1 class="h2 leftHeader">Subadmin List</h1>
         <?php }else if ($_SESSION['employeeRole'] == 'superadmin') { ?>
         <h1 class="h2 leftHeader">All Admin List</h1>
         <?php } ?>
         <!-- Admin Information Table -->
         <?php $results= mysqli_query($db, "SELECT * FROM loadtrack_admins"); 
         
         if ($_SESSION['employeeRole'] == "superadmin") {
            $results= mysqli_query($db, "SELECT * FROM loadtrack_admins");
         } else if ($_SESSION['employeeRole'] == "admin") {
            // $role = $_SESSION['employeeRole'];
            $results= mysqli_query($db, "SELECT * FROM loadtrack_admins WHERE employeeRole = 'subadmin'");
            // $query1 = "SELECT * FROM loadtrack_admins WHERE employeeRole ='subadmin'";
         } 
         ?>

         <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
               <thead class="thead-dark">
                  <tr>
                     <th>Staff ID</th>
                     <th>Name</th>
                     <th class="text-center">Role</th>
                     <th class="text-center">Password</th>
                     <th colspan="2" class="text-center">Action</th>
                  </tr>
               </thead>

               <?php while($row = mysqli_fetch_array($results)) { ?>
               <tr>
                  <td><?php echo $row['employeeID']; ?></td>
                  <td><?php echo $row['employeeName']; ?></td>
                  <td class="text-center"><?php echo $row['employeeRole']; ?></td>
                  <td class="text-center"><?php echo $row['employeePwd']; ?></td>
                  <td class="text-center">
                     <a href="index.php?edit=<?php echo $row['ID']; ?>" class="btn btn-warning edit_btn fa fa-edit" ></a>
                     <a href="server.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger del_btn fa fa-trash"></a>
                  </td>
               </tr>
         <?php } ?>
            </table>
         </div>
      </div>

      <div class="col-sm pt-3 bg-light">
         <?php 
         if ($update !== true) { ?>
         <h1 class="h2 rightHeader">Add Admin</h1>
         <?php } else { ?>
         <h1 class="h2 rightHeader">Change Password</h1>
         <?php } ?>

         <!-- Add/Update Admin -->
         <form action="" method="post" id="form1" class="bg-white"> 

            <!-- hidden id -->
            <input type="hidden" name="ID" value="<?php echo $id;?>">

            <?php if ($update !== true) { ?>
            <div class="form-group">
               <label for="employeeID">Staff ID</label>
               <input type="text" class="form-control" name="employeeID" id="employeeID" placeholder="Enter Staff ID" value="<?php echo $employeeID; ?>">
            </div>
            <div class="form-group">
               <label for="employeeName">Staff Name</label>
               <input type="text" class="form-control" name="employeeName" id="employeeName" placeholder="Enter Name" value="<?php echo $employeeName; ?>">
            </div>
            <div class="form-group">
               <label for="employeePwd">Password</label>
               <input type="text" class="form-control" name="employeePwd" placeholder="Enter Password" value="<?php echo $employeePwd; ?>">
            </div>
            <div class="form-group">
               <label for="employeeRole">Role</label>
               <?php if ($_SESSION['employeeRole'] !== 'admin') { ?>                        
                  <select class="form-control" name="employeeRole" value="<?php echo $employeeRole; ?>">
                     <option value="<?php echo $employeeRole; ?>">Select role</option>
                     <option value="superadmin">superadmin</option>
                     <option value="admin">admin</option>
                     <option value="subadmin">subadmin</option>
                  </select>
                 <?php } else { ?> 
                  <select class="form-control" name="employeeRole" value="<?php echo $employeeRole; ?>">
                     <option value="<?php echo $employeeRole; ?>">Select role</option>
                     <option value="superadmin">superadmin</option>
                  </select>
                  <?php } ?>
            </div>
            <?php } else { ?>
               <div class="form-group">
               <label for="employeeID">Staff ID</label>
               <input type="text" class="form-control" name="employeeID" id="employeeID" placeholder="Enter Staff ID" value="<?php echo $employeeID; ?>">
            </div>
            <div class="form-group">
               <label for="employeeName">Staff Name</label>
               <input type="text" class="form-control" name="employeeName" id="employeeName" placeholder="Enter Name" value="<?php echo $employeeName; ?>">
            </div>
            <div class="form-group">
               <label for="employeePwd">Password</label>
               <input type="text" class="form-control" name="employeePwd" placeholder="Enter New Password" value="<?php echo $employeePwd; ?>">
            </div>
            <?php if($_SESSION['employeeRole' !== 'admin']) { ?>
            <div class="form-group">
               <label for="employeeRole">Role (Current role: <?php echo $employeeRole; ?> )</label>                        
                  <select class="form-control" name="employeeRole" value="<?php echo $employeeRole; ?>">
                     <option value="<?php echo $employeeRole; ?>">Select New Role</option>
                     <option value="superadmin">superadmin</option>
                     <option value="admin">admin</option>
                     <option value="subadmin">subadmin</option>
               </select>
            </div>
            <?php } ?>
            <?php } ?>  

            <div class="form-group">
               <?php if ($update == true){ ?>
                  <button class="btn btn-lg btn-warning" type="submit" name="update" >Update</button>
                  <button class="btn btn-lg btn-danger" type="submit" name="cancel" >Cancel</button>
               <?php } else { ?>
                  <button class="btn btn-lg btn-success" type="submit" name="save" >Add</button>
               <?php } ?>
            </div>
         </form>

         <!-- Alert message -->
            <?php if (isset($_SESSION['message'])): ?>
               <div class="msg">
            <?php 
               echo $_SESSION['message']; 
               unset($_SESSION['message']);
            ?>
               </div>
            <?php endif ?>
      </div>
   </div>
</div>
   <script src="dashboard.js"></script>
   <script src="../assets/js/autocomplete.js"></script>
</body>
</html>