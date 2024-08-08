<?php 
   session_start();
   $db = mysqli_connect('192.168.177.20:3307', 'german', 'german', 'ECS');

   //variable initialization
   $employeeID = "";
   $employeeName = "";
   $employeeRole = "";
   $employeePwd = "";
   $ID = 0;
   $update = false;

   if (isset($_POST['save'])) {
      $employeeID = $_POST['employeeID'];
      $employeeName = $_POST['employeeName'];
      $employeeRole = $_POST['employeeRole'];
      $employeePwd = $_POST['employeePwd'];

      mysqli_query($db, "INSERT INTO loadtrack_admins (employeeID, employeeName, employeeRole, employeePwd) VALUES ('$employeeID', '$employeeName', '$employeeRole', '$employeePwd')");
      $_SESSION['message'] = "Staff information saved";
      header ('location: index.php');
   }

   if (isset($_POST['update'])) {
      $id = $_POST['ID'];
      $employeeID = $_POST['employeeID'];
      $employeeName = $_POST['employeeName'];
      $employeeRole = $_POST['employeeRole'];
      $employeePwd = $_POST['employeePwd'];

      mysqli_query($db, "UPDATE loadtrack_admins SET employeeID='$employeeID', employeeName='$employeeName', employeePwd='$employeePwd', employeeRole='$employeeRole' WHERE ID=$id");
      $_SESSION['message'] = "Staff information updated.";
      header('location: index.php');
   }

   if (isset($_POST['cancel'])) {
      header('location: index.php');
   }


   if (isset($_GET['del'])) {
      $id = $_GET['del'];
      mysqli_query($db, "DELETE FROM loadtrack_admins WHERE ID=$id");
      // $_SESSION['message'] = "Staff information deleted.";
      header('location: index.php');
   }
?>