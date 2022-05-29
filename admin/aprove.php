<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");

if (isset($_GET["appointment_id"])) {

    $appointmentId = $_GET["appointment_id"];

    // check appointment

    $query = mysqli_query($connection, "SELECT * FROM appointments WHERE appointment_id ='$appointmentId' ") or die(mysqli_error($connection));
    $data = mysqli_fetch_assoc($query);

    $date = $data["appointment_date"];
    $time = $data["appointment_time"];
    $doctor = $data["doctor_id"];

    $query = mysqli_query($connection, "SELECT * FROM doctors WHERE doctor_id ='$doctor' AND booked_date ='$date' AND booked_time ='$time'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);

    if ($count > 0) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "This doctor is already booked on that date and time",
            'data' => ''
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        mysqli_query($connection, "UPDATE appointments SET appointment_status ='approved' WHERE appointment_id ='$appointmentId'") or die(mysqli_error($connection));
        mysqli_query($connection, "UPDATE `doctors` SET `booked_date` = '$date', `booked_time` = '$time' WHERE `doctors`.`doctor_id` = '$doctor'");

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "You have successfully approved the appointment",
            'data' => $doctor
        );

        $response = json_encode($array, TRUE);
        print($response);
    }
} else {
    $array = array(
        'code' => 400,
        'status' => "error",
        'message' => "Invalid request",
        'data' => $doctor
    );

    $response = json_encode($array, TRUE);
    print($response);
}
