 <?php 
 
 //Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
print $data = json_decode($json);