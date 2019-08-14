<?php
/**
 * Created by PhpStorm.
 * User: SAIFUL
 * Date: 2/28/2019
 * Time: 9:19 AM
 */

include_once '../config/database.php';

class Post{

    //Database connection and table name
    private $conn;
    private $table_name = "posts";

    //Object properties
    public $post_id;
    public $user_phone;
    public $alt_phone;
    public $category;
    public $place_name;
    public $fee;
    public $place_address;
    public $description;
    public $place_photo;

    //user
    public $name;
    public $phone;
    public $email;
    public $photo;

    public $created_at;
    public $updated_at;

    //Constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // create post
    function create(){

        // query to insert record
        $query = "INSERT INTO ".$this->table_name."
        SET
         user_phone= :user_phone , alt_phone= :alt_phone, category= :category,
         place_name= :place_name, fee= :fee, place_address= :place_address, 
         description= :description, place_photo= :place_photo,created_at= :created_at, updated_at= :updated_at";

        //prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_phone = htmlspecialchars(strip_tags($this->user_phone));
        $this->alt_phone = htmlspecialchars(strip_tags($this->alt_phone));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->place_name = htmlspecialchars(strip_tags($this->place_name));
        $this->fee = htmlspecialchars(strip_tags($this->fee));
        $this->place_address = htmlspecialchars(strip_tags($this->place_address));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->place_photo = htmlspecialchars(strip_tags($this->place_photo));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        //bind value
        $stmt->bindParam(":user_phone", $this->user_phone);
        $stmt->bindParam(":alt_phone", $this->alt_phone);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":place_name", $this->place_name);
        $stmt->bindParam(":fee", $this->fee);
        $stmt->bindParam(":place_address", $this->place_address);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":place_photo", $this->place_photo);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()){
            return true;
        }

        return false;
    }

    // read posts
    function readAll(){

        // select all query
        $query = "SELECT * FROM posts ORDER BY post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    //read one user info
    function readOne($post_id){

        $query = "SELECT * FROM posts WHERE post_id = :post_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":post_id", $post_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->post_id = $row['post_id'];
        $this->user_phone = $row['user_phone'];
        $this->alt_phone = $row['alt_phone'];
        $this->category = $row['category'];
        $this->place_name = $row['place_name'];
        $this->fee = $row['fee'];
        $this->place_address = $row['place_address'];
        $this->description = $row['description'];
        $this->place_photo = $row['place_photo'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];

    }

    //read collection
    function readCollection($phone){

        // select all query
        $query = "SELECT posts.*, users.name, users.email, users.photo
                  FROM posts
                  LEFT JOIN users
                  ON posts.user_phone = users.phone
                  WHERE posts.user_phone =:phone ORDER BY post_id DESC";


        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":phone", $phone);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // post info update on posts table
    function update($post_id){

        $posts_query = "UPDATE posts 
        SET
         alt_phone= :alt_phone, category= :category,
         place_name= :place_name, fee= :fee, place_address= :place_address, 
         description= :description, place_photo= :place_photo, updated_at= :updated_at
         WHERE post_id= :post_id";

         //prepare query
        $stmt = $this->conn->prepare($posts_query);

         // sanitize
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));
        $this->alt_phone = htmlspecialchars(strip_tags($this->alt_phone));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->place_name = htmlspecialchars(strip_tags($this->place_name));
        $this->fee = htmlspecialchars(strip_tags($this->fee));
        $this->place_address = htmlspecialchars(strip_tags($this->place_address));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->place_photo = htmlspecialchars(strip_tags($this->place_photo));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        //bind value
        $stmt->bindParam(":post_id", $post_id);
        $stmt->bindParam(":alt_phone", $this->alt_phone);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":place_name", $this->place_name);
        $stmt->bindParam(":fee", $this->fee);
        $stmt->bindParam(":place_address", $this->place_address);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":place_photo", $this->place_photo);
        $stmt->bindParam(":updated_at", $this->updated_at);

        // execute query
        if ($stmt->execute()){
            return true;
        }

        return false;

    }

    //delete post
    function delete($post_id){

        // select all query
        $query = "DELETE FROM posts WHERE post_id= :post_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":post_id", $post_id);

        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    //Search posts
    function search($keywords){

       $query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
       FROM posts
        LEFT JOIN users
        ON posts.user_phone = users.phone
       WHERE 
       posts.post_id LIKE ? OR posts.user_phone LIKE ? OR posts.alt_phone LIKE ? OR
       posts.category LIKE ? OR posts.place_name LIKE ? OR posts.fee LIKE ? OR
       posts.place_address LIKE ? OR posts.description LIKE ? OR users.name LIKE ? OR users.email LIKE ?
       ORDER BY posts.post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%".$keywords."%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->bindParam(4, $keywords);
        $stmt->bindParam(5, $keywords);
        $stmt->bindParam(6, $keywords);
        $stmt->bindParam(7, $keywords);
        $stmt->bindParam(8, $keywords);
        $stmt->bindParam(9, $keywords);
        $stmt->bindParam(10, $keywords);

        // execute query
        $stmt->execute();
    
        return $stmt;

    }

    
    //String search
    function advance_search($string){

        $cd1 = '';
        $cd2 = '';
        $cd3 = '';
        $cd4 = '';
        $cd5 = '';

        $cd6 = '';
        $cd7 = '';
        $cd8 = '';
        $cd9 = '';
        $cd10 = '';

        /*
        split the phrase by any number of commas or space characters,
        which include " ", \r, \t, \n and \f
        output:
        Array(
            [0] => hypertext
            [1] => language
            [2] => programming
        )
        */

        $keyword_array = preg_split('/[\s]+/', $string); 
                          
        $totalKeywords = count($keyword_array); 

        for($i=0 ; $i < $totalKeywords; $i++)  {  

            /*
            $word = ":keyword".$i;
                                        
            $cd1 =$cd1. "posts.post_id LIKE $word OR ";
            $cd2 =$cd2. "posts.user_phone LIKE $word OR ";
            $cd3 =$cd3. "posts.alt_phone LIKE $word OR ";
            $cd4 =$cd4. "posts.category LIKE $word OR ";
            $cd5 =$cd5. "posts.place_name LIKE $word OR ";

            $cd6 =$cd6. "posts.fee LIKE $word OR ";
            $cd7 =$cd7. "posts.place_address LIKE $word OR ";
            $cd8 =$cd8. "posts.description LIKE $word OR ";
            $cd9 =$cd9. "users.name LIKE $word OR ";
            $cd10 =$cd10. "users.email LIKE $word OR ";
            */

            $cd1 =$cd1. "posts.post_id LIKE ? OR ";
            $cd2 =$cd2. "posts.user_phone LIKE ? OR ";
            $cd3 =$cd3. "posts.alt_phone LIKE ? OR ";
            $cd4 =$cd4. "posts.category LIKE ? OR ";
            $cd5 =$cd5. "posts.place_name LIKE ? OR ";

            $cd6 =$cd6. "posts.fee LIKE ? OR ";
            $cd7 =$cd7. "posts.place_address LIKE ? OR ";
            $cd8 =$cd8. "posts.description LIKE ? OR ";
            $cd9 =$cd9. "users.name LIKE ? OR ";
            $cd10 =$cd10. "users.email LIKE ? OR ";

        } //end foreach loop  
                          
        /*
        Syntax: substr(string,start,length)
        The substr() function returns a part of a string
        means: space,OR,space total 4 string remove
        */
        $cd10 = substr($cd10, 0, -4);
         
        //query
        $sql_query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
        FROM posts
        LEFT JOIN users
        ON posts.user_phone = users.phone
        WHERE ".$cd1.$cd2.$cd3.$cd4.$cd5.$cd6.$cd7.$cd8.$cd9.$cd10." ORDER BY posts.post_id DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($sql_query);
        
        //bind
        // foreach ($keyword_array as $key => $keyword){

        //     $keyword = htmlspecialchars(strip_tags($keyword));
        //     //each keyword concate with % symbole
        //     $foo = '%'.$keyword.'%';
        //     $stmt->bindParam(':keyword'.$key, $foo);
        // }

        //

        
          foreach($keyword_array as $key => $keyword){
            
            $stmt->bindParam($key, '%'.$keyword.'%');

          }

          //

        // for ($x = 0; $x < $totalKeywords; $x++){

        //     $keywords[$x] = "%" . $keywords[$x] . "%";  
        //     $stmt->bindParam(':search' . $x, $keywords[$x]);

        // }

        // execute query
        $stmt->execute();
        return $stmt;
     }


    //for counting total row
    function selectAll(){

        // select all query
        $query = "SELECT * FROM posts";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    //for post collection
    function post_collection($start, $limit){

        // select all query with limit
        $query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
        FROM posts
        LEFT JOIN users
        ON posts.user_phone = users.phone
        LIMIT $start, $limit";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         //bind value
         $stmt->bindParam(":start", $start);
         $stmt->bindParam(":limit", $limit);
         
        // execute query
        $stmt->execute();
        return $stmt;
    }


    //for post load
    function loadPost($limit){

        // select all query with limit
        // $query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
        // FROM posts, users ORDER BY post_id DESC LIMIT $limit";

        $query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
        FROM posts
        LEFT JOIN users
        ON posts.user_phone = users.phone 
        ORDER BY post_id DESC LIMIT $limit";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         
        // execute query
        $stmt->execute();
        return $stmt;
    }

    //for post more load 
    function loadMorePost($limit, $last_post_id){

        // select all query with limit
        $query = "SELECT posts.*, users.name, users.email, users.phone, users.photo
        FROM posts
        LEFT JOIN users
        ON posts.user_phone = users.phone
        WHERE posts.post_id < $last_post_id ORDER BY post_id DESC LIMIT $limit";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
         
        // execute query
        $stmt->execute();
        return $stmt;
    }

}