<?php
require("../configs/header.php");


if (isset($_POST["version"])) {
    $version = $_POST["version"];

    if ($version == 1.1) {
        $array = array(
            'code' => 200,
            'status' => "success",
            'message' => "App version is up to date",
            'data' => ""
        );
    } else {
        $array = array(
            'code' => 400,
            'status' => "error",
            'message' => "App version is out of date, please update your app",
            'data' => ""
        );
    }
} else {
    $array = array(
        'code' => 400,
        'status' => "error",
        'message' => "App version is missing",
        'data' => ""
    );
}

$response = json_encode($array, TRUE);
print($response);
