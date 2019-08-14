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
include_once '../objects/post.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$post = new Post($db);

//Setting tiemzone and format
$dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
$date_photo  = $dt->format('d_m_y-h_i_s');

$date  = $dt->format('Y-m-d H:i:s');

// getting data from user
$post_id = $_POST['post_id'];
$user_phone = $_POST['user_phone'];
$alt_phone = $_POST['alt_phone'];
$category = $_POST['category'];
$place_name = $_POST['place_name'];
$fee = $_POST['fee'];
$place_address = $_POST['place_address'];
$description = $_POST['description'];
$place_photo = $_POST['place_photo'];
$new_place_photo = $_POST['new_place_photo'];


if (!empty($post_id)&&
    !empty($user_phone) &&
    !empty($alt_phone) &&
    !empty($category)&&
    !empty($place_name)&&
    !empty($fee)&&
    !empty($place_address)&&
    !empty($description)&&
    !empty($place_photo)&&
    !empty($new_place_photo)){

   
    $post->post_id = $post_id;
    $post->user_phone = $user_phone;
    $post->alt_phone = $alt_phone;
    $post->category = $category;
    $post->place_name = $place_name;
    $post->fee = $fee;
    $post->place_address = $place_address;
    $post->description = $description;
    $post->place_photo = $place_photo;
    $post->updated_at = $date;



    // checking new photo
    if ($place_photo != $new_place_photo){

        // checking photo is exist
        if (file_exists("photo/".$place_photo)){

            // if exist then delete it
            if(@unlink("photo/".$place_photo)){

                //move to Photo directory 
                $photo_path = "photo/".$user_phone."_".$date_photo.".png";

                //Storing photo path on db
                $path_link = $user_phone."_".$date_photo.".png";
                $post->place_photo = $path_link;

                //base64 decoding
                $decoded_photo = base64_decode($new_place_photo);

                // updating new data and move new photo to directory
                if($post->update($post_id) && file_put_contents($photo_path, $decoded_photo)){

                    // tell the user
                    echo json_encode(array("message" => "success"));
                }
                // if unable to create the user, tell the user
                else{

                    // tell the user
                    echo json_encode(array("message:" => "Unable to update data."));

                }
            }else {
                echo json_encode(array("message" => "photo not delete"));
            }
        }else {
            echo json_encode(array("message" => "photo not exist on database"));
        }

    }else{

        $post->place_photo = $place_photo;

        // updating new data
        if($post->update($post_id)){

           
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
    echo json_encode(array("message" => "Unable to publish post. Data is incomplete."));

}