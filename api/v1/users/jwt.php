<?php


include_once '../../../acc/funcs.php';


$encodedJWT = encodeJWT('my uuid', 'John Q', 'john avatar');



$decodedJWT = decodeJWT($encodedJWT);
echo $decodedJWT;


setcookie('encodedJWT', $encodedJWT, time()+3600, "/", "localhost", false, true);



// echo $encodedJWT;
// echo "\n";
// echo json_encode($checkJWT);
// echo "\n";
// echo $decodeJWT;

// $timeNow = time();
// $time60 = time() + 60*60;

// echo $timeNow;
// echo "\n";
// echo $time60;
// echo "\n";
// echo date("d/m/Y h:m:s", $timeNow);
// echo "\n";
// echo date("d/m/Y h:m:s", $time60);
