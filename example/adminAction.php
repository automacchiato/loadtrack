<?php

//get staff id
$employeeid = $_POST['id'];

//database connection
$con = mysqli_connect("192.168.177.20:3307", "german", "german", "MSTY");

if ($employeeid != ""){

   //get corresponding employee name
   $query = mysqli_query($con, "SELECT employeename FROM employeedata WHERE employeeid='".$employeeid."'");

   $row = mysqli_fetch_array($query);

   //get the employee name
   $employeename = $row["employeename"];
   // $employeeposition = $row["position"];
} else {
   echo $employeeid;
}

   //store it in a array
$result = array($employeename);

   //send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;

?>