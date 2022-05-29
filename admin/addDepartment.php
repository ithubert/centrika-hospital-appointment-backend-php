<?php
require("../configs/header.php");
require("../configs/connection.php");
require("../configs/validate.data.php");


if (isset($_POST['department'])) {

    $department       = validate($_POST['department']);

    $query = mysqli_query($connection, "INSERT INTO `departments` (`department_id`, `department_name`) VALUES (NULL, '$department')") or die(mysqli_error($connection));

    if ($query) {

        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "You have successfully added new department",
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
