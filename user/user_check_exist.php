<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 3/8/2019
 * Time: 11:15 PM
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//Include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';


// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// set phone property of record to read
$phone = $_GET['phone'];

if (!empty($phone)){

    $user->phone = $phone;

    //Query users
    $stmt = $user->check_user($phone);
    $num = $stmt->rowCount();

    if ($num>0){

        // tell the user, exist user
        echo json_encode(
            array("message" => "exist")
        );

    }else{

        // tell the user, for new registration
        echo json_encode(
            array("message" => "success")
        );
    }

}else{
    
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}