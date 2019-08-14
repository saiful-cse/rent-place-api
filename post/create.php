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

//Setting tiemzone and format
$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
$date_photo  = $dt->format('d_m_y-h_i_s');

$date  = $dt->format('Y-m-d H:i:s');

// getting data from user
$user_phone = $_POST['user_phone'];
$alt_phone = $_POST['alt_phone'];
$category = $_POST['category'];
$place_name = $_POST['place_name'];
$fee = $_POST['fee'];
$place_address = $_POST['place_address'];
$description = $_POST['description'];
$place_photo = $_POST['place_photo'];


if (!empty($user_phone) &&
    !empty($alt_phone) &&
    !empty($category)&&
    !empty($place_name)&&
    !empty($fee)&&
    !empty($place_address)&&
    !empty($description)&&
    !empty($place_photo)){

    //Photo directory
    $photo_path = "photo/".$user_phone."_".$date_photo.".png";

    $post->user_phone = $user_phone;
    $post->alt_phone = $alt_phone;
    $post->category = $category;
    $post->place_name = $place_name;
    $post->fee = $fee;
    $post->place_address = $place_address;
    $post->description = $description;
    $post->place_photo = $user_phone."_".$date_photo.".png";
    $post->created_at = $date;
    $post->updated_at = $date;

    //base64 decoding
    $decoded_photo = base64_decode($place_photo);

    if ($post->create() && file_put_contents($photo_path, $decoded_photo)){

        // tell the user
        echo json_encode(array("message" => "success"));
    }
    // if unable to create the user, tell the user
    else{

        // tell the user
        echo json_encode(array("message:" => "Unable to publish your post"));

    }

}else{

    // tell the user
    echo json_encode(array("message" => "Unable to publish post. Data is incomplete."));

}