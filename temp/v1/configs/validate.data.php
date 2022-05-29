<?php 

function validate($data)
{
    include"db.php";
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data= mysqli_real_escape_string($connection,$data);
    return $data;
}