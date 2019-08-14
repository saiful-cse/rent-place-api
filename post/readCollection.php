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

// set phone property of record to read
$phone = $_GET['phone'];

//Query users
$stmt = $post->readCollection($phone);
$num = $stmt->rowCount();

// Check if more then 0 record found
if ($num > 0){

    // post array
    $posts_arr = array();
    $posts_arr["posts"] = array();

    // Retrieve the table contents
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        /*
         * extract row
         * this will make $row['name'] to
         * just $name only
         */
        extract($row);

        $post_item = array(
            "post_id" => $post_id,
            "user_phone" => $user_phone,
            "alt_phone" => $alt_phone,
            "category" => $category,
            "place_name" => $place_name,
            "fee" => $fee,
            "place_address" => $place_address,
            "description" => $description,
            "place_photo" => $place_photo,
            "name" => $name,
            "email" => $email,
            "photo" => $photo,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        );

        array_push($posts_arr["posts"], $post_item);
    }

    echo json_encode($posts_arr);

    //echo 'message:';

}else{
    
    // tell the admin no user found
    echo json_encode(array("message" => "no post"));
    //echo 'message:';

}