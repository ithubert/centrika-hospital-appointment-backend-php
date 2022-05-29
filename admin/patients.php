<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM patients ORDER BY patient_id DESC ") or die(mysqli_error($connection));
while ($data = mysqli_fetch_assoc($query)) {

    $patients[] = $data;

}

$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "All patients",
    'data' => $patients
);

$response = json_encode($array, TRUE);
print($response);
