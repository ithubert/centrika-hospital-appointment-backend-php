<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["user_id"])  && isset($_POST["new_password"])) {

    $userId      = validate($_POST['user_id']);

    $newPassword    = validate($_POST['new_password']);
    $newPassword    = sha1($newPassword);

    $query = mysqli_query($connection, "UPDATE users SET user_password ='$newPassword' WHERE user_id ='$userId'") or die(mysqli_error($connection));

    if ($query) {

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "You have successfully changed your password ",
            'data' => $user
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
