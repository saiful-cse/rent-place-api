<?php

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

//Getting the page number which is to be displayed  
$page = $_GET['page']; 

//Initially we show the data from 1st row that means the 0th row 
$start = 0; 
 
//Limit is 3 that means we will show 5 items at once
$limit = 5;

//Counting the total item available in the database 
$stmt1 = $post->selectAll();
$total = $stmt1->rowCount();

//We can go atmost to page number total/limit
$page_limit = $total/$limit;

if($page<=$page_limit){

    //Calculating start for every given page number 
    $start = ($page - 1) * $limit; 

    //getting data from db with limit
    $stmt2 = $post->post_collection($start, $limit);

    // post array
    $posts_arr = array();
    $posts_arr["posts"] = array();

    // Retrieve the table contents
    while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){

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

