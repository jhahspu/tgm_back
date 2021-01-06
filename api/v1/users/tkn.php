<?php


include_once '../../../acc/database.php';
include_once '../../../acc/users.php';
include_once '../../../acc/funcs.php';


$database = new Database();
$db = $database->connect();
$usr = new User($db);
$data = json_decode(file_get_contents('php://input', false));


if (empty($data->token)) return json_response(400, "Token required");


$result = $usr->checkToken($data->token);
if ($result) {
  echo json_response(200, "user found");
} else {
  echo json_response(404, "user not found");
}