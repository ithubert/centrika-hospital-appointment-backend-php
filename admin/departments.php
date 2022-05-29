<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM departments ORDER BY department_id DESC ") or die(mysqli_error($connection));
while ($data = mysqli_fetch_assoc($query)) {

    $departmentId = $data["department_id"];

    $query2 = mysqli_query($connection,"SELECT * FROM doctors WHERE department_id ='$departmentId'")or die(mysqli_error($connection));
    $doctors = mysqli_num_rows($query2);
    $data["doctors"]= $doctors;

    $departments[] = $data;

}

$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "All departments",
    'data' => $departments
);

$response = json_encode($array, TRUE);
print($response);
