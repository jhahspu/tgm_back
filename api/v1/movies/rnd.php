<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once '../../../acc/database.php';
include_once '../../../acc/movies.php';


$database = new Database();
$db = $database->connect();
$mvs = new Mvs($db);


isset($_GET['genre']) ? $temp = $_GET['genre'] : $temp = "";
$result = $mvs->getRnd($temp);
$num = $result->rowCount();
if($num > 0) {
  $mvs_arr = array();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $mvs_item = array(
      'id' => $id,
      'tmdb' => $tmdb_id,
      'title' => $title,
      'release_date' => $release_date,
      'runtime' => $runtime,
      'genres' => $genres,
      'overview' => $overview,
      'poster' => $poster,
      'trailers' => $trailers
    );
    array_push($mvs_arr, $mvs_item);
  }
  echo json_encode($mvs_arr);
} else {
  echo json_encode(
    array('message' => 'No Movies Found')
  );
}