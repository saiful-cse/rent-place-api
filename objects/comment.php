<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 3/2/2019
 * Time: 4:25 PM
 */
class Comment{

    //Database connection and table name
    private $conn;
    private $table_name = "comments";

    public $comment_id;
    public $user_phone;
    public $comment;
    public $created_at;
    public $updated_at;

    //Constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    //create comment
    function create()
    {

        // query to insert record
        $query = "INSERT INTO " . $this->table_name . "
        SET
         user_phone= :user_phone , comment= :comment, created_at= :created_at";

        //prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_phone = htmlspecialchars(strip_tags($this->user_phone));
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        //bind value
        $stmt->bindParam(":user_phone", $this->user_phone);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":created_at", $this->created_at);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // read feedback
    function readComment(){

        // select all query
        $query = "SELECT * FROM comments ORDER BY comment_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }
}