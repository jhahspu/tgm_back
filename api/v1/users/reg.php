<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../../acc/database.php';
include_once '../../../acc/users.php';


$database = new Database();
$db = $database->connect();
$usr = new User($db);
$data = json_decode(file_get_contents('php://input', false));


if (empty($data->email)) { 
  return json_encode(array(
    "status" => "error",
    "message" => "email required"
  ));
}
if (empty($data->pass)) { 
  return json_encode(array(
    "status" => "error",
    "message" => "password required"
  ));
}


$result = $usr->registerUser($data->email, $data->pass);
echo $result;