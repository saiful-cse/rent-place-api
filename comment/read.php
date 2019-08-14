<?php


//Required headers
header("Access-Control-Allow-Origin");
header("Content-Type: application/json; charset=UTF-8");

//Include database and ojbect files
include_once '../config/database.php';
include_once '../objects/comment.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$comment = new Comment($db);

//Query users
$stmt = $comment->readComment();
$num = $stmt->rowCount();

// Check if more then 0 record found
if ($num>0){

    // users array
    $comment_arr = array();
    $comment_arr["comment"] = array();

    // Retrieve the table contents
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        /*
         * extract row
         * this will make $row['name'] to
         * just $name only
         */
        extract($row);

        $comment_item = array(
            "comment_id" => $comment_id,
            "user_phone" => $user_phone,
            "comment" => $comment,
            "created_at" => $created_at,
        );

        array_push($comment_arr["comment"], $comment_item);
    }

    echo json_encode($comment_arr);
}else{


    // tell the admin no user found
    echo json_encode(
      array("message" => "No comment found")
    );
}