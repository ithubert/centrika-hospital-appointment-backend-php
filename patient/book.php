<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['patient_fname']) && isset($_POST['patient_lname']) && isset($_POST["patient_phone"]) && isset($_POST["patient_email"]) && isset($_POST["doctor_id"]) && isset($_POST["date"]) && isset($_POST["time"])) {

    $firstName          = validate($_POST['patient_fname']);
    $lastName           = validate($_POST['patient_lname']);
    $patientPhone       = validate($_POST['patient_phone']);
    $patientEmail       = validate($_POST['patient_email']);
    $date               = validate($_POST['date']);
    $time               = validate($_POST['time']);
    $doctorId           = validate($_POST['doctor_id']);

    // convert date and time

    $date = date("Y-m-d", strtotime($date));
    $time = date("h:00:00", strtotime($time));

    $query = mysqli_query($connection, "SELECT * FROM patients WHERE patient_email ='$patientEmail' OR patient_phone ='$patientPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $patient = mysqli_fetch_assoc($query);

    if ($count > 0) {

        $patientId = $patient["patient_id"];
    } else {

        $query = mysqli_query($connection, "INSERT INTO `patients` (`patient_id`, `patient_fname`, `patient_lname`, `patient_email`, `patient_phone`,  `patient_password`,`patient_status`) 
        VALUES (NULL, '$firstName', '$lastName', '$patientEmail', '$patientPhone','$patientPassword', 'active')") or die(mysqli_error($connection));
        $patientId = mysqli_insert_id($connection);
    }



    mysqli_query($connection, "INSERT INTO `appointments` (`appointment_id`, `doctor_id`, `patient_id`, `appointment_date`, `appointment_time`, `appointment_comment`, `appointment_status`) 
    VALUES (NULL, '$doctorId', '$patientId', '$date', '$time', NULL, 'pending')") or die(mysqli_error($connection));

    $array = array(
        'code' => 200,
        'status' => "success",
        'message' => "You have successfully booked your appointment, we will comfirm soon",
        'data' => ''
    );
    
} else {
    $array = array(
        'code' => 400,
        'status' => "error",
        'message' => "missing data",
        'data' => ''
    );
}

$response = json_encode($array, TRUE);
print($response);
