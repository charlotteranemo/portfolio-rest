<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, DELETE, POST');
header('Access-Control-Allow-Origin: http://charlotteranemo.se');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Content-Type');

require 'config/Database.php';
require 'classes/Websites.php';

$method = $_SERVER['REQUEST_METHOD'];

//Läser in id
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}

//Ansluter till databas
$database = new Database();
$db = $database->connect();

$websites = new Websites($db);

switch ($method) {
    case 'GET':
        if(isset($id)) {
            $result = $websites->readOne($id);
        } else {
            $result = $websites->read();
        }

        if(sizeof($result) > 0) { //Om minst en webbsida hittas
            http_response_code(200);
        } else {
            http_response_code(404);
            $result = array("message" => "No websites found");
        }
        
    break;
    case 'POST':
        //Hämtar inmatad data
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->webName) && !empty($data->url) && !empty($data->description) && !empty($data->img)) { //Kollar om tomma inputfält
            $websites->name = $data->webName;
            $websites->url = $data->url;
            $websites->description = $data->description;
            $websites->img = $data->img;
    
            if($websites->create()) {
                http_response_code(201);
                $result = array("message" => "Website added");
            } else {
                http_response_code(503);
                $result = array("message" => "Website could not be added");
            }
        } else {
            http_response_code(503);
            $result = array("message" => "Website could not be added. Missing data.");
        }
        
    break;
    case 'PUT':
        if(!isset($id)) { //Kollar om ID är satt
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else { //Om ID är satt, sätt variablerna i klassen Websites till datan i inputfälten
            $data = json_decode(file_get_contents("php://input"));

            $websites->name = $data->webName;
            $websites->url = $data->url;
            $websites->description = $data->description;
            $websites->img = $data->img;

            if($websites->update($id)) { //Kör funktionen update och skicka med id
                http_response_code(201);
                $result = array("message" => "Website updated");
            } else {
                http_response_code(503);
                $result = array("message" => "Website could not be updated");
            }
        }
    break;
    case 'DELETE':
        if(!isset($id)) {
            http_response_code(510);
            $result = array("message" => "Missing ID");
        } else {
            if($websites->delete($id)) {
                http_response_code(201);
                $result = array("message" => "Website deleted");
            } else {
                http_response_code(503);
                $result = array("message" => "Website could not be deleted");
            }
        }
    break;

    
}

//Skriv ut meddelandet som satts
echo json_encode($result);

?>