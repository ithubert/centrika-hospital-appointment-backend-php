<?php
header('Content-Type: application/json');
require("../configs/connection.php");
require("../configs/validate.data.php");

$query = mysqli_query($connection, "SELECT * FROM categories ORDER BY category_name ASC") or die(mysqli_error($connection));
while ($data = mysqli_fetch_assoc($query)) {


    $categories[] = $data;


    // $count = mysqli_num_rows($subSubQuery);
    // if($count ==0)
    // {
    //     $data["sub_categories"] = null;
    // }
    // $categories[] = $data;

}

$array = array(
    'code' => 200,
    'status' => "success",
    'message' => "All Categories",
    'data' => $categories
);

$response = json_encode($array, TRUE);
print($response);
