<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

if (isset($_GET["appointment_id"])) {

    $appointmentId = $_GET["appointment_id"];
    
    mysqli_query($connection,"UPDATE appointments SET appointment_status ='rejected' WHERE appointment_id ='$appointmentId'")or die(mysqli_error($connection));

    $array = array(
        'code' => 200,
        'status' => "success",
        'message' => "You have successfully rejected the appointment",
        'data' => $doctor
    );

    $response = json_encode($array, TRUE);
    print($response);
}else{
    $array = array(
        'code' => 400,
        'status' => "error",
        'message' => "Invalid request",
        'data' => $doctor
    );

    $response = json_encode($array, TRUE);
    print($response);
}
