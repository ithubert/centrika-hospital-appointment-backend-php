<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM doctors, departments WHERE departments.department_id = doctors.department_id ORDER BY doctor_id DESC ") or die(mysqli_error($connection));
while ($data = mysqli_fetch_assoc($query)) {

    $doctors[] = $data;

}

$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "All Doctors",
    'data' => $doctors
);

$response = json_encode($array, TRUE);
print($response);
