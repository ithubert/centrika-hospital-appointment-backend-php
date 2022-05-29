<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST["admin_email"]) && isset($_POST["admin_password"])) {

    $admin_email     = validate($_POST['admin_email']);
    $password    = validate($_POST['admin_password']);
    $password    = sha1($password);

    $query = mysqli_query($connection, "SELECT * FROM admins WHERE admin_email='$admin_email' AND admin_password ='$password'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);
    $admin = mysqli_fetch_assoc($query);

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

         
            $admin["admin_password"] = null;

           
            $array = array(
                'code' => 200,
                'status' => "success",
                'message' => "You have successfully logged in ",
                'data' => $admin
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
