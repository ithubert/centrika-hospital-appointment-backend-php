<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['admin_fname']) && isset($_POST['admin_lname']) && isset($_POST["admin_phone"]) && isset($_POST["admin_email"]) ) {

    $password = sha1("password123");
    
    $firstName        = validate($_POST['admin_fname']);
    $lastName        = validate($_POST['admin_lname']);
    $adminPhone       = validate($_POST['admin_phone']);
    $adminEmail       = validate($_POST['admin_email']);

    $adminPassword    =  sha1("password123");

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


    $query = mysqli_query($connection, "SELECT * FROM admins WHERE admin_email ='$adminEmail' OR admin_phone ='$adminPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $admin = mysqli_fetch_assoc($query);

    if ($count > 0) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "A admin with these information is already registered!",
            'data' => $admin
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        $query = mysqli_query($connection, "INSERT INTO `admins` (`admin_id`, `admin_fname`, `admin_lname`, `admin_email`, `admin_phone`,  `admin_password`,`admin_status`) 
        VALUES (NULL, '$firstName', '$lastName', '$adminEmail', '$adminPhone','$adminPassword', 'active')") or die(mysqli_error($connection));

        if ($query) {

            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully registered on H-Appointment",
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
