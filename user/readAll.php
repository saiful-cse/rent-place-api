<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/27/2019
 * Time: 12:25 PM
 */

//Required headers
header("Access-Control-Allow-Origin");
header("Content-Type: application/json; charset=UTF-8");

//Include database and ojbect files
include_once '../config/database.php';
include_once '../objects/user.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$user = new User($db);

//Query users
$stmt = $user->readAll();
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