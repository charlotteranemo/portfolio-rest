<?php

class Courses {
    
    private $conn;
    private $table = "courses";
  
    //Properties
    public $id;
    public $name;
    public $start;
    public $end;
    public $school;
  
    //Constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function create() {
        
        //Rensar bort html-taggar och tecken
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->start = htmlspecialchars(strip_tags($this->start));
        $this->end = htmlspecialchars(strip_tags($this->end));
        $this->school = htmlspecialchars(strip_tags($this->school));

        //SQL insert
        $query = "INSERT INTO $this->table (name, start, end, school) VALUES ('$this->name', '$this->start', '$this->end', '$this->school')";

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
        $this->school = htmlspecialchars(strip_tags($this->school));

        //SQL Update med värdena som är inmatade
        $query = "UPDATE $this->table SET name = '$this->name', start = '$this->start', end = '$this->end', school = '$this->school' WHERE id = '$id'";

        $res = $this->conn->prepare($query);
            
        if($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function read() {
        $courseArr = array();

        //Select alla kurser för utläsning
        $query = "SELECT * FROM $this->table";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        //Lägger till kurs för kurs i arrayen courseArr
        foreach($res as $row) {
            $aCourse=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "start" => $row['start'],
                "end" => $row['end'],
                "school" => $row['school']
            );
      
            array_push($courseArr, $aCourse);
        }
  
        return $courseArr;
    }

    //Läser ut en post i listan
    function readOne($id) {
        $query = "SELECT * FROM $this->table WHERE id = $id";
        $res = $this->conn->prepare($query);
        
        $res->execute();

        foreach($res as $row) {
            $aCourse=array(
                "id" => $row['id'],
                "name" => $row['name'],
                "start" => $row['start'],
                "end" => $row['end'],
                "school" => $row['school']
            );
        }
  
        //Returnerar kursen som lästs ut
        return $aCourse;
    }
}




?>