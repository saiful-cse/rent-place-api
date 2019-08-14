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
include_once '../objects/user.php';

//function for load post
function loadUser($limit){

    //Instantiate database and user object
    $database = new Database();
    $db = $database->getConnection();

    //Initialize object
    $user = new User($db);

    //Query post
    $stmt = $user->loadUser($limit);
    $num = $stmt->rowCount();

    // Check if more then 0 record found
    if ($num>0){

        // users array
        $users_arr = array();
        $users_arr["users"] = array();

        // Retrieve the table contents
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            /*
            * extract row
            * this will make $row['name'] to
            * just $name only
            */
            extract($row);

            $user_item = array(
                "id" => $id,
                "phone" => $phone,
                "name" => $name,
                "email" => $email,
                "address" => $address,
                "photo" => $photo,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );

            array_push($users_arr["users"], $user_item);
        }

        echo json_encode($users_arr);
    }else{

        // tell the admin no user found
        echo json_encode(
        array("message" => "No user found")
        );
    }

}


//function for load more post
function loadMoreUser($limit, $last_user_id){

    //Instantiate database and user object
    $database = new Database();
    $db = $database->getConnection();

    //Initialize object
    $user = new User($db);

    //Query post
    $stmt = $user->loadMoreUser($limit, $last_user_id);
    $num = $stmt->rowCount();

    // Check if more then 0 record found
    if ($num>0){

        // users array
        $users_arr = array();
        $users_arr["users"] = array();

        // Retrieve the table contents
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            /*
            * extract row
            * this will make $row['name'] to
            * just $name only
            */
            extract($row);

            $user_item = array(
                "id" => $id,
                "phone" => $phone,
                "name" => $name,
                "email" => $email,
                "address" => $address,
                "photo" => $photo,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );

            array_push($users_arr["users"], $user_item);
        }

        echo json_encode($users_arr);
    }else{

        echo json_encode(
        array("message" => "No post found")
        );
    }

}



if(!isset($_GET['last_id'])){

    $limit = $_GET['limit'];
    loadUser($limit);

}else{

    $limit = $_GET['limit'];
    $last_user_id = $_GET['last_id'];
    loadMoreUser($limit, $last_user_id);
}

