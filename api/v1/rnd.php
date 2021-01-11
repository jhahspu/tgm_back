<?php
include_once '../../acc/database.php';
include_once '../../acc/movies.php';
include_once '../../acc/funcs.php';


$data = json_decode(file_get_contents('php://input', false));


if ($data->req === "rnd-titles") {
  $database = new Database();
  $db = $database->connect();
  $mvs = new Mvs($db);
  $genre = $data->genre;
  $result = $mvs->getRnd($genre);
  echo $result;
} else {
  echo json_response(400, "Invalid request");
}
