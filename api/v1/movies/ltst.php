<?php
include_once '../../../acc/database.php';
include_once '../../../acc/movies.php';


$database = new Database();
$db = $database->connect();
$mvs = new Mvs($db);


$result = $mvs->getLtst();
echo $result;