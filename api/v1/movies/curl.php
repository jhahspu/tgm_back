<?php


include_once '../../../acc/database.php';
include_once '../../../acc/users.php';
include_once '../../../acc/movies.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['tkn'])) {
    $database = new Database();
    $db = $database->connect();
    $usr = new User($db);
    $tkn_passed = $usr->checkToken($_POST['tkn']);
    if ($tkn_passed) {
      if (isset($_POST['mid'])) {
        $mId = $_POST['mid'];
        $mvs = new Mvs($db);
        $mId_present = $mvs->checkTMDbId($mId);
          if ($mId_present) {
            echo json_response(405, "Movie already in the database");
          } else {
            $res = getDataFromTmdb($mId);
            echo $res;
          }
      } else {
        echo json_response(400, "Provide tMDb ID");
      }
    } else {
      echo json_response(404, "Invalid token");
    }
  } else {
    echo json_response(400, "Provide a token");
  }
}

