<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["user_phone"])) {
    $userPhone = validate($_POST["user_phone"]);

    $query = mysqli_query($connection, "SELECT * FROM users WHERE user_phone ='$userPhone'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $user = mysqli_fetch_assoc($query);

    if ($count < 1) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "Your phone number is not registered on Humanly",
            'data' => $user
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {


        // OTP CODE 

        $digits_needed = 6;
        $random_number = ''; // set up a blank string

        $count = 0;

        while ($count < $digits_needed) {
            $random_digit = mt_rand(0, 9);

            $random_number .= $random_digit;
            $count++;
        }

        $code = $random_number;

        $phone = $userPhone;
        $sms = "Your Humanly Password Reset code is " . $code;

        mysqli_query($connection, "DELETE FROM pwd_reset_codes WHERE user_phone ='$userPhone'") or die(mysqli_error($connection));

        mysqli_query($connection, "INSERT INTO `pwd_reset_codes` (`user_phone`, `code`) VALUES ('$userPhone', '$code')") or die(mysqli_error($connection));

        require("../configs/sms.php");

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "We have sent you a password reset Code on your phone",
            'data' => ''
        );

        $response = json_encode($array, TRUE);
        print($response);
    }
}
