<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['user_id']) && isset($_POST['user_fname']) && isset($_POST["user_lname"]) && isset($_POST["user_phone"]) && isset($_POST["user_email"])) {

    $userFname       = validate($_POST['user_fname']);
    $userLname       = validate($_POST['user_lname']);
    $userPhone       = validate($_POST['user_phone']);
    $userEmail       = validate($_POST['user_email']);
    $userId          = validate($_POST['user_id']);



    $query = mysqli_query($connection, "UPDATE `users` SET `user_lname` = '$userLname', `user_fname` = '$userFname', `user_phone` = '$userPhone', `user_email` = '$userEmail' WHERE `users`.`user_id` = '$userId'") or die(mysqli_error($connection));

    if ($query) {

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "You have successfully updated your profile",
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
