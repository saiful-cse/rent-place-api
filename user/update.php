<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 3/5/2019
 * Time: 12:29 PM
 */

// required headers
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
$new_name = $_POST['new_name'];
$new_email = $_POST['new_email'];
$new_address = $_POST['new_address'];
$new_photo = $_POST['new_photo'];

if (!empty($phone) &&
    !empty($new_name) &&
    !empty($new_email)&&
    !empty($new_address)&&
    !empty($new_photo)){

    $user->phone = $phone;
    $user->name = $new_name;
    $user->email = $new_email;
    $user->address = $new_address;
    $user->updated_at = $date;

    // checking new photo
    if ($phone != $new_photo){

        // checking photo is exist
        if (file_exists("photo/".$phone.".png")){

            // if exist then delete it
            if(@unlink("photo/".$phone.".png")){

                $photo_path = "photo/".$phone.".png";
                $user->photo = $phone.".png";

                // updating new data and move new photo to directory
                if($user->update($phone) && file_put_contents($photo_path,base64_decode($new_photo))){

                    // tell the user
                    echo json_encode(array("message" => "success"));
                }
                // if unable to create the user, tell the user
                else{

                    // tell the user
                    echo json_encode(array("message:" => "Unable to update data."));

                }
            }
        }

    }else{

        $user->photo = $phone.".png";

        // updating new data
        if($user->update($phone)){

            // tell the user
            echo json_encode(array("message" => "success"));
        }
        // if unable to create the user, tell the user
        else{

            // tell the user
            echo json_encode(array("message:" => "Unable to update data."));
        }
    }

}else{
   
    // tell the user
    echo json_encode(array("message" => "Unable to update data. Data is incomplete."));

}

