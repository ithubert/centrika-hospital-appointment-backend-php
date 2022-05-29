<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM appointments, doctors, patients WHERE doctors.doctor_id = appointments.doctor_id AND patients.patient_id = appointments.patient_id AND appointment_status ='pending' ORDER BY appointment_id ASC LIMIT 10") or die(mysqli_error($connection));
while ($data = mysqli_fetch_assoc($query)) {


    $appointments[] = $data;


}

$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "10 first Appointments",
    'data' => $appointments
);

$response = json_encode($array, TRUE);
print($response);
