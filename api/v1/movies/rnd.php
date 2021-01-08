<?php
include_once '../../../acc/database.php';
include_once '../../../acc/movies.php';


$database = new Database();
$db = $database->connect();
$mvs = new Mvs($db);


$headerCookies = explode("; ", getallheaders()["Cookie"]);
$cookies = array();
foreach($headerCookies as $itm) {
  list($key, $val) = explode("=", $itm, 2);
  $cookies[$key] = $val;
}




isset($_GET['genre']) ? $temp = $_GET['genre'] : $temp = "";
$result = $mvs->getRnd($temp);
echo $result;
echo "\n";
if ($cookies["encodedJWT"]) echo json_encode($cookies["encodedJWT"]);