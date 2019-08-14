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
include_once '../objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// set phone property of record to read
$phone = $_GET['phone'];

//read the details of user to be edited or posting
$user->readOne($phone);
//$num = $stmt->rowCount();

if ($user->name != null){

    $user_item = array(
        "id" =>$user->id,
        "phone" =>$user->phone,
        "name" =>$user->name,
        "email" =>$user->email,
        "address" =>$user->address,
        "photo" =>$user->photo,
        "created_at" =>$user->created_at,
        "updated_at" =>$user->updated_at
    );

  
    // make it json format
    echo json_encode($user_item);


}else{
    
   echo json_encode(array("message" => "account has been removed"));
    
}