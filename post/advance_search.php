<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
//include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/post.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$post = new Post($db);

$string = $_POST['s'];
//Query posts
$stmt = $post->advance_search($string);
$num = $stmt->rowCount();

//Check if more than 0 record found
if ($num>0){

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

}else{

    
    // tell the admin no user found
    echo json_encode(array("message" => "no post found"));

}


