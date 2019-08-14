<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/27/2019
 * Time: 12:25 PM
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//Include database and ojbect files
include_once '../config/database.php';
include_once '../objects/post.php';

//function for load post
function loadPost($limit){

    //Instantiate database and user object
    $database = new Database();
    $db = $database->getConnection();

    //Initialize object
    $post = new Post($db);

    //Query post
    $stmt = $post->loadPost($limit);
    $num = $stmt->rowCount();

    // Check if more then 0 record found
    if ($num>0){

        // users array
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
        echo json_encode(
        array("message" => "No post found")
        );
    }

}


//function for load more post
function loadMorePost($limit, $last_post_id){

    //Instantiate database and user object
    $database = new Database();
    $db = $database->getConnection();

    //Initialize object
    $post = new Post($db);

    //Query post
    $stmt = $post->loadMorePost($limit, $last_post_id);
    $num = $stmt->rowCount();

    // Check if more then 0 record found
    if ($num>0){

        // users array
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

        echo json_encode(
        array("message" => "No post found")
        );
    }

}



if(!isset($_GET['last_id'])){

    $limit = $_GET['limit'];
    loadPost($limit);

}else{

    $limit = $_GET['limit'];
    $last_post_id = $_GET['last_id'];
    loadMorePost($limit, $last_post_id);
}

