<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST["user_phone"]) && isset($_POST["user_email"])  && isset($_POST["user_password"])) {

    $firstName        = validate($_POST['first_name']);
    $lastName        = validate($_POST['last_name']);
    $userPhone       = validate($_POST['user_phone']);
    $userEmail       = validate($_POST['user_email']);
    $userPassword    = validate($_POST['user_password']);
    $userPassword    = sha1($userPassword);

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

    $apiKey = getRandomString();



    $query = mysqli_query($connection, "SELECT * FROM users WHERE user_email ='$userEmail' OR user_phone ='$userPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $user = mysqli_fetch_assoc($query);

    if ($count > 0) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "A user with these information is already registered!",
            'data' => $user
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        $query = mysqli_query($connection, "INSERT INTO `users` (`user_id`, `user_fname`, `user_lname`, `user_email`, `user_phone`, `user_country`, `user_token`, `user_password`,`user_status`) 
        VALUES (NULL, '$firstname', '$lastname', '$userEmail', '$userPhone', NULL, NULL,'$userPassword', 'active')") or die(mysqli_error($connection));

        if ($query) {

            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully registered on Humanly",
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
