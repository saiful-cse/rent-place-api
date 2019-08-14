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
include_once '../objects/comment.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$comment = new Comment($db);

//Setting tiemzone and format
$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
$date  = $dt->format('Y-m-d H:i:s');

// getting data from user
$user_phone = $_POST['user_phone'];
$user_comment = $_POST['comment'];

if (!empty($user_phone) &&
    !empty($comment)){

    $comment->user_phone = $user_phone;
    $comment->comment = $user_comment;
    $comment->created_at = $date;


    if ($comment->create()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Your comment has been submitted."));
    }
    // if unable to create the user, tell the user
    else{

        //set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message:" => "Unable to submit your comment"));

    }

}else{
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to submit your comment. Data is incomplete."));

}