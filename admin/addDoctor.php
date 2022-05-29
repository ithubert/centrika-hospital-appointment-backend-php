<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['doctor_fname']) && isset($_POST['doctor_lname']) && isset($_POST["doctor_phone"]) && isset($_POST["doctor_email"]) && isset($_POST["department_id"]) ) {

    $password = sha1("password123");
    
    $firstName        = validate($_POST['doctor_fname']);
    $lastName          = validate($_POST['doctor_lname']);
    $doctorPhone       = validate($_POST['doctor_phone']);
    $doctorEmail       = validate($_POST['doctor_email']);
    $departmentId     = validate($_POST['department_id']);

    $doctorPassword    =  sha1("password123");

    $digits_needed = 6;
    $random_number = ''; // set up a blank string

    $count = 0;

    while ($count < $digits_needed) {
        $random_digit = mt_rand(0, 9);

        $random_number .= $random_digit;
        $count++;
    }

    $code = "MN" . $random_number;

    // GENERATE API KEY 

    function getRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }


    $query = mysqli_query($connection, "SELECT * FROM doctors WHERE doctor_email ='$doctorEmail' OR doctor_phone ='$doctorPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $doctor = mysqli_fetch_assoc($query);

    if ($count > 0) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "A doctor with these information is already registered!",
            'data' => $doctor
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        $query = mysqli_query($connection, "INSERT INTO `doctors` (`doctor_id`, `department_id`, `doctor_fname`, `doctor_lname`, `doctor_email`, `doctor_phone`,  `doctor_password`,`doctor_status`)
        VALUES (NULL,'$departmentId', '$firstName', '$lastName', '$doctorEmail', '$doctorPhone','$doctorPassword', 'available')") or die(mysqli_error($connection));

        if ($query) {

            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully registered a doctor on Centrika Hospital Appointment",
                'data' => ''
            );

            $response = json_encode($array, TRUE);
            print($response);
        } else {
            $array = array(
                'code' => 500,
                'status' => "error",
                'message' => "something wrong",
                'data' => ''
            );

            $response = json_encode($array, TRUE);
            print($response);
        }
    }
} else {
    $array = array(
        'code' => 400,
        'status' => "error",
        'message' => "Incomplete data",
        'data' => ''
    );

    $response = json_encode($array, TRUE);
    print($response);
}
