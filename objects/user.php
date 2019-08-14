<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/27/2019
 * Time: 12:06 PM
 */
class User{

    //Database connection and table name
    private $conn;
    private $table_name = "users";

    //Object properties
    public $id;
    public $phone;
    public $name;
    public $email;
    public $address;
    public $photo;
    public $created_at;
    public $updated_at;

    //for comment
    public $user_phone;
    public $comment;


    //Constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read users info
    function readAll(){

        // select all query
        $query = "SELECT * FROM users ORDER BY id DESC ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }


    //read one user info
    function readOne($phone){

        $query = "SELECT * FROM users WHERE phone = :phone";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":phone", $phone);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->phone = $row['phone'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->address = $row['address'];
        $this->photo = $row['photo'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];

    }


    // check user is exist
    function check_user($phone){

        // query to select record
        $query = "SELECT phone FROM ".$this->table_name." WHERE phone = :phone";

        // prepare query
        $stmt = $this->conn->prepare($query);

        //bind value
        $stmt->bindParam(":phone",$phone);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create user
    function create(){

        // query to insert record
        $query = "INSERT INTO ".$this->table_name."
        SET
         phone= :phone , name= :name, email= :email,
         address= :address, photo= :photo, created_at= :created_at, updated_at= :updated_at";

        //prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        //bind value
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()){
            return true;
        }
        return false;

    }

    // user info update on users, posts and comments table
    function update($phone){

        $users_query = "UPDATE users SET name = :new_name, email = :new_email 
                          ,address = :new_address,photo = :photo ,updated_at = :updated_at WHERE phone = :phone";


        //prepare query
        $stmt_users = $this->conn->prepare($users_query);

        // sanitize
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->photo = htmlspecialchars(strip_tags($this->photo));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind value for user table
        $stmt_users->bindParam(":phone", $phone);
        $stmt_users->bindParam(":new_name", $this->name);
        $stmt_users->bindParam(":new_email", $this->email);
        $stmt_users->bindParam(":new_address", $this->address);
        $stmt_users->bindParam(":photo", $this->photo);
        $stmt_users->bindParam(":updated_at", $this->updated_at);


        if ($stmt_users->execute()){

            return true;
        }
        return false;

    }


    //for user load
    function loadUser($limit){

        $query = "SELECT * FROM users ORDER BY id DESC LIMIT $limit";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         
        // execute query
        $stmt->execute();
        return $stmt;
    }

    //for user more load 
    function loadMoreUser($limit, $last_user_id){

        // select all query with limit
        $query = "SELECT * FROM users WHERE id < $last_user_id ORDER BY id DESC LIMIT $limit";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
         
        // execute query
        $stmt->execute();
        return $stmt;
    }


    // check user is exist
    function comment_add(){

        // query to select record
        $query = "INSERT INTO comments
        SET user_phone= :user_phone, comment= :comment, created_at= :created_at";

        // prepare query
        $stmt = $this->conn->prepare($query);

         // sanitize
         $this->user_phone = htmlspecialchars(strip_tags($this->user_phone));
         $this->comment = htmlspecialchars(strip_tags($this->comment));
         $this->created_at = htmlspecialchars(strip_tags($this->created_at));

        //bind value
        $stmt->bindParam(":user_phone", $this->user_phone);
        $stmt->bindParam(":comment",$this->comment);
        $stmt->bindParam(":created_at",$this->created_at);

        // execute query
        if ($stmt->execute()){
            return true;
        }

        return false;
        
    }



}