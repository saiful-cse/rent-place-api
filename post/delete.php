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
include_once '../objects/post.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$post = new Post($db);


// getting data from user
$post_id = $_POST['post_id'];
$place_photo = $_POST['place_photo'];

if (!empty($post_id)){

    $post->post_id = $post_id;

    if ($post->delete($post_id) && @unlink("photo/".$place_photo)){

        // tell the user
        echo json_encode(array("message" => "success"));
    }
    // if unable to create the user, tell the user
    else{

        // tell the user
        echo json_encode(array("message:" => "Unable to delete your post"));

    }

}else{
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to publish post. Data is incomplete."));

}