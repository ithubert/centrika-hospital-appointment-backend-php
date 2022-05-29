<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["user_phone"]) && isset($_POST["code"])) {

    $userPhone    = $_POST["user_phone"];
    $code         = $_POST["code"];

    $query = mysqli_query($connection, "SELECT * FROM pwd_reset_codes WHERE user_phone ='$userPhone' AND code ='$code'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);

    if ($count == 1) {
        $query = mysqli_query($connection, "SELECT * FROM users WHERE user_phone ='$userPhone'") or die(mysqli_error($connection));
        $data = mysqli_fetch_assoc($query);


        mysqli_query($connection, "DELETE FROM pwd_reset_codes WHERE user_phone ='$userPhone' AND code ='$code'" )or die(mysqli_error($connection));

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "Your Password Reset Code is valid",
            'data' => $data
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "Your Password Reset Code is invalid or Expired",
            'data' => ''
        );

        $response = json_encode($array, TRUE);
        print($response);
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
