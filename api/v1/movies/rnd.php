<?php
include_once '../../../acc/database.php';
include_once '../../../acc/movies.php';


$database = new Database();
$db = $database->connect();
$mvs = new Mvs($db);


isset($_GET['genre']) ? $temp = $_GET['genre'] : $temp = "";
$result = $mvs->getRnd($temp);
echo $result;