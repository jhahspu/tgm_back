<?php
include_once '../../../acc/database.php';
include_once '../../../acc/movies.php';
include_once '../../../acc/funcs.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if (isset($_POST['req'])) {

    if ($_POST['req'] === 'rnd-titles'){
      
      // TODO
      $database = new Database();
      $db = $database->connect();
      $mvs = new Mvs($db);

      isset($_POST['genre']) ? $temp = $_POST['genre'] : $temp = "any";
      $result = $mvs->getRnd($temp);
      echo $result;

    }

  }
  


} else {
  header("Location: /");
  die();
}





// $headerCookies = explode("; ", getallheaders()["Cookie"]);
// $cookies = array();
// foreach($headerCookies as $itm) {
//   list($key, $val) = explode("=", $itm, 2);
//   $cookies[$key] = $val;
// }





