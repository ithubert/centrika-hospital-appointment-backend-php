<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM doctors , departments WHERE doctors.department_id = departments.department_id ") or die(mysqli_error($connection));
$doctors = mysqli_num_rows($query);

 $home["doctors"] = $doctors;

$query = mysqli_query($connection, "SELECT * FROM  patients  ") or die(mysqli_error($connection));
$patients = mysqli_num_rows($query);

$home["patients"] = $patients;

$query = mysqli_query($connection, "SELECT * FROM  appointments WHERE appointment_status ='pending' ") or die(mysqli_error($connection));
$appointments = mysqli_num_rows($query);

$home["appointments"] = $appointments;


$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "Home data",
    'data' => $home
);

$response = json_encode($array, TRUE);
print($response);
