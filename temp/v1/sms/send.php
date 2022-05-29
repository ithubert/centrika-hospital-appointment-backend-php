<?php
header('Content-Type: application/json');
require("../configs/db.php");
require("../configs/validate.data.php");

// CHECK API KEY 

if (isset($_POST["API_KEY"])) {
    $apiKey = $_POST["API_KEY"];

    $query = mysqli_query($connection, "SELECT * FROM users WHERE api_key ='$apiKey'") or die(mysqli_error($connection));
    $count = mysqli_num_rows($query);

    // IF API KEY IS VALID
    if ($count == 1) {

        $user = mysqli_fetch_assoc($query);
        $userId = $user["user_id"];

        if ($user["user_balance"] >= 1) {


            // IF ALL REQUIRED INFO ARE VALID
            if (isset($_POST["sender"]) && isset($_POST["receivers"]) && isset($_POST["message"])) {


                $sender = $_POST["sender"];
                $receivers = $_POST["receivers"];
                $message = $_POST["message"];
                $dlrurl = "https://sms.devslab.io/api/status.php";

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://www.intouchsms.co.rw/api/sendsms/.json",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => array('sender' => $sender, 'recipients' => $receivers, 'message' => $message, 'dlrurl' => $dlrurl),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Basic dHV5aXNlbmdlLmh1YmVydDpASW50b3VjaDQ1NDUx"
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);


                $data = json_decode($response, true);

                $status = $data["details"][0]["status"];
                $credits = $data["details"][0]["credits"];
                $cost = $data["details"][0]["cost"];
                $messageId = $data["details"][0]["messageid"];
                $message = $data["details"][0]["message"];
                $message = validate($message);
                $receipient = $data["details"][0]["receipient"];

                $success = $data["success"];
                $credits = $data["summary"]["credits"];
                $time = $data["summary"]["time"];
                $balance = $data["summary"]["balance"];
                $totalmessages = $data["summary"]["totalmessages"];


                # SAVE SENDER ID

                $query = mysqli_query($connection, "SELECT * FROM senders WHERE sender_name ='$sender' AND user_id ='$userId'") or die(mysqli_error($connection));
                $count = mysqli_num_rows($query);
                $senderInfo = mysqli_fetch_assoc($query);
                if ($count == 0) {
                    mysqli_query($connection, "INSERT  INTO `senders` (`sender_id`, `user_id`, `sender_name`, `sender_status`) VALUES (NULL, '$userId', '$sender', 'active')") or die(mysqli_error($connection));
                    $senderId = mysqli_insert_id($connection);
                } else {
                    $senderId = $senderInfo["sender_id"];
                }
                # SAVE MESSAGE TO DB 


                $query = mysqli_query($connection, "INSERT INTO `messages` (`message_id`, `ext_message_id`, `sender_id`, `user_id`, `campain_id`, `carrier_id`, `message_subject`, `message`, `receiver`,`message_status`, `message_sent_time`, `message_updated_time`) 
    VALUES (NULL, '$messageId', '$senderId', '$userId', NULL, '1', NULL, '$message', '$receivers','$status', current_timestamp(), current_timestamp())");

                $messageId = mysqli_insert_id($connection);
                #UPDATE INTERNAL BALANCE

                mysqli_query($connection, "UPDATE `gateways` SET `gateway_balance` = '$balance' WHERE `gateways`.`gateway_id` = 1") or die(mysqli_error($connection));

                # SAVE CONTACT

                //mysqli_query($connection,"INSERT INTO `contacts` (`contact_id`, `user_id`, `contact_fname`, `contact_lname`, `contact_company`, `contact_phone`, `contact_email`, `contact_status`) VALUES (NULL, '2', NULL, NULL, NULL, '$receivers', NULL, 'pending')")or die(mysqli_error($connection));




                # SEND BACK RESPONSE

                $response = array();

                $response["message_id"] = $messageId;
                $response["receiver"] = $receipient;
                $response["units"] = $credits;
                $response["status"] = $status;
                $response["time"] = $time;


                $response = json_encode($response, TRUE);
                print $response;

                // UPDATE BALANCE
                $query = mysqli_query($connection, "UPDATE users SET user_balance = user_balance-'$credits' WHERE user_id ='$userId'") or die(mysqli_error($connection));
            } else {
                $array = array(
                    'code' => 401,
                    'status' => "error",
                    'message' => "Invalid request",
                    'data' => ''
                );

                $response = json_encode($array, TRUE);
                print($response);
            }
        } else {
            $array = array(
                'code' => 400,
                'status' => "error",
                'message' => "Your Balance is low",
                'data' => ''
            );

            $response = json_encode($array, TRUE);
            print($response);
        }
    } else {
        $array = array(
            'code' => 401,
            'status' => "error",
            'message' => "Invalid API KEY",
            'data' => ''
        );

        $response = json_encode($array, TRUE);
        print($response);
    }
} else {
    $array = array(
        'code' => 401,
        'status' => "error",
        'message' => "API KEY is missing",
        'data' => ''
    );

    $response = json_encode($array, TRUE);
    print($response);
}
