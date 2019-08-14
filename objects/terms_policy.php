<?php

class Terms_Policy{

    //Database connection and table name
    private $conn;
    private $table_name1 = "terms";
    private $table_name2 = "policy";

    //Object properties
    public $id;
    public $title;
    public $description;

    //Constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read terms
    function readTerms(){

        // select all query
        $query = "SELECT * FROM terms";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // read policy
    function readPolicy(){

        // select all query
        $query = "SELECT * FROM policy";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }


}