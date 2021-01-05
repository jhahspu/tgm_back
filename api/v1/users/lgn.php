<?php
include_once '../../../acc/database.php';
include_once '../../../acc/users.php';


$database = new Database();
$db = $database->connect();
$usr = new User($db);
$data = json_decode(file_get_contents('php://input', false));


if (empty($data->email)) return json_response(400, "Email required");
if (empty($data->pass)) return json_response(400, "Password required");

if ($data->req == 'signin') {
  $result = $usr->checkUser($data->email, $data->pass);
  echo $result;
}
if ($data->req == 'register') {
  $result = $usr->registerUser($data->email, $data->pass);
  echo $result;
}
