<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['patient_fname']) && isset($_POST['patient_lname']) && isset($_POST["patient_phone"]) && isset($_POST["patient_email"]) ) {

    $password = sha1("password123");
    
    $firstName        = validate($_POST['patient_fname']);
    $lastName        = validate($_POST['patient_lname']);
    $patientPhone       = validate($_POST['patient_phone']);
    $patientEmail       = validate($_POST['patient_email']);

    $patientPassword    =  sha1("password123");

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

    function getRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }


    $query = mysqli_query($connection, "SELECT * FROM patients WHERE patient_email ='$patientEmail' OR patient_phone ='$patientPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $patient = mysqli_fetch_assoc($query);

    if ($count > 0) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "A patient with these information is already registered!",
            'data' => $patient
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        $query = mysqli_query($connection, "INSERT INTO `patients` (`patient_id`, `patient_fname`, `patient_lname`, `patient_email`, `patient_phone`,  `patient_password`,`patient_status`) 
        VALUES (NULL, '$firstName', '$lastName', '$patientEmail', '$patientPhone','$patientPassword', 'active')") or die(mysqli_error($connection));

        if ($query) {

            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully registered on H-Appointments",
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
