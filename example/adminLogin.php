<?php

   //Start session
   session_start();

   if (isset($_SESSION['employeeID'])){
      header("Location: indexAdmin.php");
      exit();
   };

   //Include database connectivity

   include_once('adminConfig.php');

   if(isset($_POST['submit'])){
      $errorMsg = "";
      $employeeID = $connection->real_escape_string($_POST['employeeID']);
      $employeePwd = $connection->real_escape_string(($_POST['employeePwd']));

      if (!empty($employeeID) || !empty($employeePwd)) {
         $query = "SELECT * FROM loadtrack_admins WHERE employeeID = '$employeeID' AND employeePwd= '$employeePwd'";
         $result = $connection->query($query);

         if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['employeeID'] = $row['employeeID'];
            $_SESSION['employeeName'] = $row['employeeName'];
            $_SESSION['employeeRole'] = $row['employeeRole'];
            if (($_SESSION['employeeID'] == 'superadmin') || ($_SESSION['employeeID'] == 'admin')) {
              header("Location:admin_dashboard/index.php");
             } else {
              header("Location:indexAdmin.php");
             };
            // header("Location:admin_dashboard/index.php");
            die();
         } else {
            $errorMsg = "No user found with this Staff ID or Password. Please try again.";
         }
      } else {
         $errorMsg = "Staff ID and Password is required!";
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="vendor/bootstrap-4.6.1-dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="assets/img/admin.png"/>
  <link href="assets/css/signin.css" rel="stylesheet">
  
  <script src="vendor/bootstrap4.0/js/jquery-3.5.1.min.js"></script>
  <script src="assets/js/loginPage.js"></script>

</head>

<body class="text-center">
    <main class="form-signin">

      <?php if (isset($errorMsg)) { ?>
         <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"></button>
            <?php echo $errorMsg; ?>
         </div>
      <?php } ?>

      <form action="" method="POST">
          <img class="mb-4" src="assets/img/admin.png" alt="" width="70" height="70">
          <h1 class="h3 mb-3 fw-normal">ECS Load Track Data Web System</h1>
          <div id="empID">
            <input type="text" class="form-control"  name="employeeID" placeholder="Staff ID">
          </div>
          <div>
            <input id="myPwd" type="password" class="form-control" name="employeePwd" placeholder="Password" required>
          </div>
          <div class="checkbox mb-3">
            <label>
              <input type="checkbox" onclick="showPassword()" value="show-password"> Show Password
            </label>
          </div>
        <button name="submit" value ="login" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      </form>
    </main>

    <script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
  </body>


</html>