<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/28/2019
 * Time: 9:25 AM
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//Include database and object files
include_once '../config/database.php';
include_once '../objects/post.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$post = new Post($db);

// set $ads_id property of record to read
$post_id = $_GET['post_id'];

//read the details of post to be edited or posting
$post->readOne($post_id);
//$num = $stmt->rowCount();

if ($post->user_phone != null){

    $post_item = array(
        "post_id" => $post->post_id,
        "user_phone" => $post->user_phone,
        "alt_phone" => $post->alt_phone,
        "category" => $post->category,
        "place_name" => $post->place_name,
        "fee" => $post->fee,
        "place_address" => $post->place_address,
        "description" => $post->description,
        "place_photo" => $post->place_photo,
        "created_at" => $post->created_at,
        "updated_at" => $post->updated_at
    );

    // make it json format
    echo json_encode($post_item);

}else{
    echo json_encode(array("message" => "Post has been deleted"));
}