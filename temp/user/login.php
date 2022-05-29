<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["user_phone"]) && isset($_POST["user_password"])) {

    $user_phone       = validate($_POST['user_phone']);
    $password    = validate($_POST['user_password']);
    $password    = sha1($password);

    $query = mysqli_query($connection, "SELECT * FROM users WHERE user_phone ='$user_phone' AND user_password ='$password'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $user = mysqli_fetch_assoc($query);

    if ($count < 1) {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "Account with these information doesn't exist !",
            'data' => ""
        );

        $response = json_encode($array, TRUE);
        print($response);
    } else {

        if ($query) {
            // GENERATE TOKEN 

            function getRandomString($length = 500)
            {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $string = '';

                for ($i = 0; $i < $length; $i++) {
                    $string .= $characters[mt_rand(0, strlen($characters) - 1)];
                }

                return $string;
            }

            $token = getRandomString();

            $userId = $user["user_id"];
            mysqli_query($connection, "UPDATE users SET user_token ='$token' WHERE user_id ='$userId'") or die(mysqli_error($connection));

            $user["user_token"] = $token;
            $user["user_password"] = null;

           
            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully logged in ",
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
