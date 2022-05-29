<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["user_id"]) && isset($_POST["current_password"]) && isset($_POST["new_password"])) {

    $userId      = validate($_POST['user_id']);
    $currentPassword    = validate($_POST['current_password']);
    $currentPassword    = sha1($currentPassword);

    $newPassword    = validate($_POST['new_password']);
    $newPassword    = sha1($newPassword);

    $query = mysqli_query($connection, "SELECT * FROM users WHERE user_id ='$userId' AND user_password ='$currentPassword'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $user = mysqli_fetch_assoc($query);

    if ($count < 1) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "Current password is wrong.",
            'data' => ""
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        $query = mysqli_query($connection,"UPDATE users SET user_password ='$newPassword'")or die(mysqli_error($connection));

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
