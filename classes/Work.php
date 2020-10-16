<?php

class Work {
    
    private $conn;
    private $table = "work";
  
    //Properties
    public $id;
    public $name;
    public $start;
    public $end;
    public $title;
  
    //Constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function create() {
        
        //Rensar bort html-taggar och tecken
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->end = htmlspecialchars(strip_tags($this->end));
        $this->title = htmlspecialchars(strip_tags($this->title));

        //SQL insert
        $query = "INSERT INTO $this->table (name, start, end, title) VALUES ('$this->name', '$this->start', '$this->end', '$this->title')";

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
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->end = htmlspecialchars(strip_tags($this->end));
        $this->title = htmlspecialchars(strip_tags($this->title));

        //SQL Update med värdena som är inmatade
        $query = "UPDATE $this->table SET name = '$this->name', start = '$this->start', end = '$this->end', title = '$this->title' WHERE id = '$id'";

        $res = $this->conn->prepare($query);
            
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function read() {
        $workArr = array();

        //Select alla arbetsplatser för utläsning
        $query = "SELECT * FROM $this->table";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        //Lägger till arbetsplats för arbetsplats i arrayen workArr
        foreach($res as $row) {
            $aWork=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "start" => $row['start'],
                "end" => $row['end'],
                "title" => $row['title']
            );
      
            array_push($workArr, $aWork);
        }
  
        return $workArr;
    }

    //Läser ut en post i listan
    function readOne($id) {
        $query = "SELECT * FROM $this->table WHERE id = $id";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        foreach($res as $row) {
            $aWork=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "start" => $row['start'],
                "end" => $row['end'],
                "title" => $row['title']
            );
        }
  
        //Returnerar arbetsplatsen som lästs ut
        return $aWork;
    }
}

?>