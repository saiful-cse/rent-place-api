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
include_once '../objects/user.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$user = new User($db);

//Setting tiemzone and format
$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
$date  = $dt->format('Y-m-d H:i:s');

// getting data from user
$phone = $_POST['phone'];
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$photo = $_POST['photo'];

if (!empty($phone) &&
    !empty($name) &&
    !empty($email)&&
    !empty($address) &&
    !empty($photo)){

    //Photo directory
    $photo_path = "photo/".$phone.".png";

    $user->phone = $phone;
    $user->name = $name;
    $user->email = $email;
    $user->address = $address;
    $user->photo = $phone.".png";
    $user->created_at = $date;
    $user->updated_at = $date;

    //Query users
    $stmt = $user->check_user($phone);
    $num = $stmt->rowCount();

    if ($num>0){


        // tell the user, exist user
        echo json_encode(
            array("message" => "exist")
        );

    }//create the user
    else if ($user->create() && file_put_contents($photo_path,base64_decode($photo))){


        // tell the user
        echo json_encode(array("message" => "success"));
    }
    // if unable to create the user, tell the user
    else{

        // tell the user
        echo json_encode(array("message:" => "Unable to create user."));

    }

}else{
   
    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));

}