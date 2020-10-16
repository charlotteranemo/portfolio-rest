<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, DELETE, POST');
header('Access-Control-Allow-Origin: http://charlotteranemo.se');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Content-Type');

require 'config/Database.php';
require 'classes/Courses.php';

$method = $_SERVER['REQUEST_METHOD'];

//Läser in id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Ansluter till databas
$database = new Database();
$db = $database->connect();

$courses = new Courses($db);

switch ($method) {
    case 'GET':
        if(isset($id)) {
            $result = $courses->readOne($id);
        } else {
            $result = $courses->read();
        }

        if(sizeof($result) > 0) { //Om minst en kurs hittas
            http_response_code(200);
        } else {
            http_response_code(404);
            $result = array("message" => "No courses found");
        }
        
    break;
    case 'POST':
        //Hämtar inmatad data
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->courseName) && !empty($data->courseStart) && !empty($data->courseEnd) && !empty($data->school)) { //Kollar om tomma inputfält
            $courses->name = $data->courseName;
            $courses->start = $data->courseStart;
            $courses->end = $data->courseEnd;
            $courses->school = $data->school;
    
            if($courses->create()) {
                http_response_code(201);
                $result = array("message" => "Course added");
            } else {
                http_response_code(503);
                $result = array("message" => "Course could not be added");
            }
        } else {
            http_response_code(503);
            $result = array("message" => "Course could not be added. Missing data.");
        }
        
    break;
    case 'PUT':
        if(!isset($id)) { //Kollar om ID är satt
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else { //Om ID är satt, sätt variablerna i klassen Courses till datan i inputfälten
            $data = json_decode(file_get_contents("php://input"));

            $courses->name = $data->courseName;
            $courses->start = $data->courseStart;
            $courses->end = $data->courseEnd;
            $courses->school = $data->school;

            if($courses->update($id)) { //Kör funktionen update och skicka med id
                http_response_code(201);
                $result = array("message" => "Course updated");
            } else {
                http_response_code(503);
                $result = array("message" => "Course could not be updated");
            }
        }
    break;
    case 'DELETE':
        if(!isset($id)) {
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else {
            if($courses->delete($id)) {
                http_response_code(201);
                $result = array("message" => "Course deleted");
            } else {
                http_response_code(503);
                $result = array("message" => "Course could not be deleted");
            }
        }
    break;

    
}

//Skriv ut meddelandet som satts
echo json_encode($result);

?>