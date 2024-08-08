<?php
   session_start();
   $db = mysqli_connect("192.168.177.20:3307","german","german","ECS");

   //initialize variables
   $employeeID = "";
   $employeeName = "";
   $employeePwd = "";
   $id = 0;
   $update = false;

   if (isset($_POST['save'])) {
      $employeeID = $_POST['employeeID'];
      $employeeName = $_POST['employeeName'];
      $employeePwd = $_POST['employeePwd'];

      mysqli_query($db, "INSERT INTO loadtrack_admins (employeeID, employeeName, employeePwd) VALUES ('$employeeID','$employeeName','$employeePwd')");
      $_SESSION['message'] = "New Staff information has been added.";
      header('location: index.php');
   }

   if (isset($_POST['update'])) {
      $id = $_POST['ID'];
      $employeeID = $_POST['employeeID'];
      $employeeName = $_POST['employeeName'];
      $employeePwd = $_POST['employeePwd'];

      mysqli_query($db, "UPDATE loadtrack_admins SET employeeID='$employeeID', employeeName='$employeeName', employeePwd='$employeePwd' WHERE ID=$id");
      $_SESSION['message'] = "Staff information updated.";
      header('location: index.php');
   }

   if (isset($_GET['del'])) {
      $id = $_GET['del'];
      mysqli_query($db, "DELETE FROM loadtrack_admins WHERE id=$id");
      $_SESSION['message'] = "Staff information deleted.";
      header('location: index.php');
   }
?>