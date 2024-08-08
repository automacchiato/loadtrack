<?php

   session_start();

   include_once('adminConfig.php');

   if (!isset($_SESSION['employeeID'])){
      header("Location:adminLogin.php");
      exit();
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

   <link href="vendor/bootstrap-4.6.1-dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/adminDashboard.css">
   <link rel="stylesheet" type="text/css" href="vendor/bootstrap-table-master/dist/bootstrap-table.min.css">
   <link rel="icon" type="image/png" href="assets/img/admin.png"/>

   <script src="vendor/bootstrap4.0/js/jquery-3.5.1.min.js"></script>
   <script src="vendor/bootstrap-table-master/dist/bootstrap-table.min.js"></script>
   <script src="vendor/bootstrap4.0/js/bootstrap.js"></script>

</head>
<body>

            <!-- EDIT POP-UP FORM (MODAL) -->
            <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                        <div class="model-header">
                           <h5 class="modal-title" id="exampleModalLabel">Edit Admin Data</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>

                        <form action="updatecode.php" method="post">
                           <div class="modal-body">
                              <input type="text" name="update_id" id="update_id" value="<?php echo $ID; ?>">

                              <div class="form-group">
                                 <label> Staff ID </label>
                                 <input type="text" name="employeeID" id="employeeID" class="form-control" value="<?php echo $employeeID; ?>">
                              </div>

                              <div class="form-group">
                                 <label> Name </label>
                                 <input type="text" name="employeeName" id="employeeName" class="form-control" value="<?php echo $employeeName; ?>">
                              </div>

                              <div class="form-group">
                                 <label for="employeeRole">Role</label>                        
                                 <?php if ($_SESSION['employeeRole'] == 'admin') {?>
                                    <input class="form-control" type="text" value="subadmin" disabled>
                                 <?php }else { ?>
                                    <select class="form-control" name="employeeRole">
                                       <option value="<?php echo $employeeRole ?>">Select role</option>
                                       <option value="superadmin">superadmin</option>
                                       <option value="admin">admin</option>
                                       <option value="subadmin">subadmin</option>
                                    </select>
                                 <?php } ?>
                              </div>

                              <div class="form-group">
                                 <label> Password </label>
                                 <input type="text" name="employeePwd" id="employeePwd" class="form-control" value="<?php echo $employeePwd; ?>">
                              </div>

                           </div>
                           <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                 <button type="submit" name="updateData" class="btn btn-primary">Update</button>
                           </div>
                        </form>
                  </div>
               </div>
            </div>

            <!-- DELETE POP UP FORM MODAL -->
            <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Admin Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                        </button>
                     </div>
                     <form action="deletecode.php" method="post">
                        <div class="modal-body">
                           <input type="hidden" name="delete_id" id="delete_id">
                           <h4>Are you sure you want to delete this data?</h4>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                           <button type="submit" class="btn btn-primary" name="deletedata">Yes</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>

   <!-- Navbar -->
   <nav class="navbar navbar-info sticky-top flex-md-nowrap p-10 navbar-dark bg-primary">
      <a id="nav1" class="navbar-brand col-sm-3 col-md-2 mr-0" href=""><b>Dashboard</b></a>
      <input type="hidden" id="employeeRole" value="<?php echo $_SESSION["employeeRole"] ?>">
         <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link text-white" href="adminLogout.php">Hi, <?php echo strtok($_SESSION['employeeName'],""); ?>
               <button class="btn btn-danger btn-sm py-10" style="margin-left: 10px">Log Out</button>
            </a>
            </li>
         </ul>
   </nav>

   <div class="container-fluid">
      <div class="row">
         <nav id="nav2" class="col-md-2 d-none d-md-block bg-dark sidebar">
            <div class="sidebar-sticky">
               <ul id="ul1" class="nav flex-column">
                  <li class="nav-item">
                     <a id="mainBtn" class="text-white active nlink nav-link mt-2">
                        <span data-feather="home"></span> Home <span class="sr-only">(current)</span>
                     </a>
                  </li>
                     <li>
                        <a id="addAdminBtn"class="text-white nlink nav-link"><span data-feather="users"></span>Add Admin</a>
                     </li>
               </ul>
            </div>
         </nav>

         <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 h-100">
            <div id="mainSection">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
               <?php 
                  if ($_SESSION['employeeRole'] == 'admin') { ?>
               <h1 class="h2">Subadmin List</h1>
               <?php }else { ?>
               <h1 class="h2">All Admin List</h1>
               <?php } ?>
            </div>


            <!-- VIEW TABLE -->
            <div class="table-responsive">
               <table class="table table-hover">
                  <thead>
                     <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Password</th>
                        <th colspan="2">Action</th>
                     </tr>
                  </thead>

                  <tbody>
                     <?php 
                        if ($_SESSION['employeeRole'] == "superadmin") {
                           $query1 = "SELECT * FROM loadtrack_admins";
                        } else {
                           $role = $_SESSION['employeeRole'];
                           $query1 = "SELECT * FROM loadtrack_admins WHERE employeeRole ='subadmin'";
                        }

                        $result1 = $connection->query($query1);

                        if ($result1->num_rows > 0){
                              while ($row = $result1->fetch_array()) {
                        ?>

                        <tr>
                           <td><?php echo $row['employeeID']?></td>
                           <td><?php echo $row['employeeName']?></td>
                           <td><?php echo $row['employeeRole']?></td>
                           <td><?php echo $row['employeePwd']?></td>
                           <td>
                              <button type="button" class="btn btn-success editbtn"> EDIT </button>
                              <button type="button" class="btn btn-danger deletebtn"> DELETE </button>
                           </td>
                        </tr>

                        <?php }

                        } else {
                           echo "<h2> No record found! </h2>";
                        } ?>

                  </tbody>
               </table>
            </div>    
            </div>

            <!-- ADD ADMIN  -->
            <div id="addAdmin" style="display: none">
               <?php 
               
               include_once('adminConfig.php');

               if (isset($_POST['submit'])) {
                  $employeeID = $connection->real_escape_string($_POST['employeeID']);
                  $employeeName = $connection->real_escape_string($_POST['employeeName']);
                  $employeePwd = $connection->real_escape_string($_POST['employeePwd']);
                  $employeeRole = $connection->real_escape_string($_POST['employeeRole']);

                  $query2 = "INSERT INTO loadtrack_admins (employeeID, employeeName, employeePwd, employeeRole) VALUES ('$employeeID','$employeeName', '$employeePwd','$employeeRole')";
                  $_SESSION['message'] = "New Staff information has been added."; 
                  $result2 = $connection->query($query2);
               }
               ?>

               <h1 class="h2">Add Admin</h1><br>
               <form action="" method="post">
                  <div class="form-group">
                     <label for="employeeID">Staff ID</label>
                     <input type="text" class="form-control" name="employeeID" id="employeeID" placeholder="Enter Staff ID">
                  </div>
                  <div class="form-group">
                     <label for="employeeName">Staff Name</label>
                     <input type="text" class="form-control" name="employeeName" id="employeeName" placeholder="Enter Name">
                  </div>
                  <div class="form-group">
                     <label for="employeePwd">Password</label>
                     <input type="text" class="form-control" name="employeePwd" placeholder="Enter Password" value="msty1234">
                  </div>
                  <div class="form-group">
                     <label for="employeeRole">Role</label>                        
                     <?php if ($_SESSION['employeeRole'] == 'admin') {?>
                        <input class="form-control" type="text" value="subadmin" disabled>
                     <?php }else { ?>
                        <select class="form-control" name="employeeRole">
                           <option value="">Select role</option>
                           <option value="superadmin">superadmin</option>
                           <option value="admin">admin</option>
                           <option value="subadmin">subadmin</option>
                     </select>
                     <?php } ?>
                  </div>
                  <div class="form-group">
                     <input class="btn btn-success" type="submit" name=submit>
                     <input class="btn btn-danger" type="reset" name=reset value="Reset">
                  </div>
               </form>
            </div>

         </main>
      </div>
   </div>
   <script src="assets/js/adminDashboard.js"></script>
   <script src="vendor/unpkg/feather.min.js"></script>
   <script src="assets/js/autocomplete.js"></script>
   <script>
      if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
      }
   </script>
</body>
</html>