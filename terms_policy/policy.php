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
include_once '../objects/terms_policy.php';

//Instantiate database and user object
$database = new Database();
$db = $database->getConnection();

//Initialize object
$policy = new Terms_Policy($db);

//Query users
$stmt = $policy->readPolicy();
$num = $stmt->rowCount();

// Check if more then 0 record found
if ($num>0){

    // policy array
    $terms_arr["policy"] = array();

    // Retrieve the table contents
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        /*
         * extract row
         * this will make $row['name'] to
         * just $name only
         */
        extract($row);

        $policy_item = array(
            "id" => $id,
            "title" => $title,
            "description" => $description
        );

        array_push($terms_arr["policy"], $policy_item);
    }

    // set response code - 200 OK
    http_response_code(200);
    echo json_encode($terms_arr);
}else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the admin no user found
    echo json_encode(
      array("message" => "No policy found")
    );
}