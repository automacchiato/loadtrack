<?php

//Declare variables
$servername = "192.168.177.20:3307";
$username = "german";
$pw = "german";
$dbName = "ECS";

//Connect to DB
$connection = mysqli_connect($servername,$username,$pw,$dbName);

//Check connection
if (!$connection) {
   die ("Connection fail: ". mysqli_connect_error());
}

?>