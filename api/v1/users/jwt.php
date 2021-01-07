<?php


include_once '../../../acc/funcs.php';


$encodedJWT = encodeJWT('my uuid', 'John Q', 'john avatar');
$checkJWT = checkJWT($encodedJWT);
$decodeJWT = decodeJWT($encodedJWT);

echo $encodedJWT;
echo "\n";
echo json_encode($checkJWT);
echo "\n";
echo $decodeJWT;

// setcookie('encodedJWT', $encodedJWT, time()+3600, "/", "localhost", false, true);

// $timeNow = time();
// $time60 = time() + 60*60;

// echo $timeNow . "->-" . $time60;
// echo ' / ';
// echo date("d/m/Y h:m:s ->-", $timeNow);
// echo date("d/m/Y h:m:s", $time60);
