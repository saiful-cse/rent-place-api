<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/27/2019
 * Time: 2:00 PM
 */

// Requires headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$user = new User($db);

//Setting tiemzone and format
$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
$date  = $dt->format('Y-m-d H:i:s');

// getting data from user
$user_phone = $_POST['user_phone'];
$comment = $_POST['comment'];

if (!empty($user_phone) &&
    !empty($comment)){

    $user->user_phone = $user_phone;
    $user->comment = $comment;
    $user->created_at = $date;

    if ($user->comment_add()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "success"));
    }
    // if unable to create the user, tell the user
    else{

        //set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message:" => "Unable to send your post"));

    }

}else{
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to send comment. Data is incomplete."));

}