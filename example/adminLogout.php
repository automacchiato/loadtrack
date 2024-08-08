<?php
   session_start();
   session_destroy();
   session_unset($_SESSION['employeeName']);
   session_unset($_SESSION['employeeRole']);
   session_unset($_SESSION['employeeID']);
   header("Location: index.php");
   die();
?>