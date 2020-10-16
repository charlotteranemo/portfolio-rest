<?php

class Websites {
    
    private $conn;
    private $table = "websites";
  
    //Properties
    public $id;
    public $name;
    public $url;
    public $description;
    public $img;
  
    //Constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function create() {
        
        //Rensar bort html-taggar och tecken
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->img = htmlspecialchars(strip_tags($this->img));

        //SQL insert
        $query = "INSERT INTO $this->table (name, url, description, img) VALUES ('$this->name', '$this->url', '$this->description', '$this->img')";

        $res = $this->conn->prepare($query);
            
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
        
    }

    function delete($id) {
        $query = "DELETE from $this->table WHERE id = $id";

        $res = $this->conn->prepare($query);
            
        //Returnerar true om den lyckats ta bort post med id = id
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function update($id) {

        //Rensar bort html-taggar och tecken
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->img = htmlspecialchars(strip_tags($this->img));

        //SQL Update med värdena som är inmatade
        $query = "UPDATE $this->table SET name = '$this->name', url = '$this->url', description = '$this->description', img = '$this->img' WHERE id = '$id'";

        $res = $this->conn->prepare($query);
            
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function read() {
        $websitesArr = array();

        //Select alla webbsidor för utläsning
        $query = "SELECT * FROM $this->table";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        //Lägger till webbsida för webbsida i arrayen websitesArr
        foreach($res as $row) {
            $aWebsite=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "url" => $row['url'],
                "description" => $row['description'],
                "img" => $row['img']
            );
      
            array_push($websitesArr, $aWebsite);
        }
  
        return $websitesArr;
    }

    //Läser ut en post i listan
    function readOne($id) {
        $query = "SELECT * FROM $this->table WHERE id = $id";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        foreach($res as $row) {
            $aWebsite=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "url" => $row['url'],
                "description" => $row['description'],
                "img" => $row['img']
            );
        }
  
        //Returnerar webbsidan som lästs ut
        return $aWebsite;
    }
}

?>