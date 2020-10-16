<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, DELETE, POST');
header('Access-Control-Allow-Origin: http://charlotteranemo.se');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Content-Type');

require 'config/Database.php';
require 'classes/Work.php';

$method = $_SERVER['REQUEST_METHOD'];

//Läser in id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Ansluter till databas
$database = new Database();
$db = $database->connect();

$work = new Work($db);

switch ($method) {
    case 'GET':
        if(isset($id)) {
            $result = $work->readOne($id);
        } else {
            $result = $work->read();
        }

        if(sizeof($result) > 0) { //Om minst en arbetsplats hittas
            http_response_code(200);
        } else {
            http_response_code(404);
            $result = array("message" => "No workplaces found");
        }
        
    break;
    case 'POST':
        //Hämtar inmatad data
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->workName) && !empty($data->workStart) && !empty($data->workEnd) && !empty($data->title)) { //Kollar om tomma inputfält
            $work->name = $data->workName;
            $work->start = $data->workStart;
            $work->end = $data->workEnd;
            $work->title = $data->title;
    
            if($work->create()) {
                http_response_code(201);
                $result = array("message" => "Workplace added");
            } else {
                http_response_code(503);
                $result = array("message" => "Workplace could not be added");
            }
        } else {
            http_response_code(503);
            $result = array("message" => "Workplace could not be added. Missing data.");
        }
        
    break;
    case 'PUT':
        if(!isset($id)) { //Kollar om ID är satt
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else { //Om ID är satt, sätt variablerna i klassen Work till datan i inputfälten
            $data = json_decode(file_get_contents("php://input"));

            $work->name = $data->workName;
            $work->start = $data->workStart;
            $work->end = $data->workEnd;
            $work->title = $data->title;

            if($work->update($id)) { //Kör funktionen update och skicka med id
                http_response_code(201);
                $result = array("message" => "Workplace updated");
            } else {
                http_response_code(503);
                $result = array("message" => "Workplace could not be updated");
            }
        }
    break;
    case 'DELETE':
        if(!isset($id)) {
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else {
            if($work->delete($id)) {
                http_response_code(201);
                $result = array("message" => "Workplace deleted");
            } else {
                http_response_code(503);
                $result = array("message" => "Workplace could not be deleted");
            }
        }
    break;

    
}

//Skriv ut meddelandet som satts
echo json_encode($result);

?>