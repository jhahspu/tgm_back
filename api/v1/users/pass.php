<?php
include_once '../../../acc/database.php';
include_once '../../../acc/users.php';
include_once '../../../acc/funcs.php';


$database = new Database();
$db = $database->connect();
$usr = new User($db);
$data = json_decode(file_get_contents('php://input', false));


if (empty($data->token)) return json_response(400, "Token required");
if (empty($data->oldpass)) return json_response(400, "Old password required");
if (empty($data->newpass)) return json_response(400, "New password required");


$result = $usr->changePassword($data->token, $data->oldpass, $data->newpass);
echo $result;