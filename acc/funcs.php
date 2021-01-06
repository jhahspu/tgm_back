<?php


include_once 'acc.php';


/** Return JSON response with status codes
 * 200 - OK
 * 201 - Created
 * 204 - Updated, shouldn't contain message
 * 400 - Bad request
 * 404 - Not found
 * 405 - Method not allowed
 * 500 - Server Internal error
 */
function json_response($code = 200, $message = null, $data = null) {
  header_remove();
  http_response_code($code);
  header('Access-Control-Allow-Origin: *');
  header("Cache-Control: no-cache,public,max-age=300,s-maxage=900");
  header('Content-Type: application/json');
  $status = array(
      200 => 'OK',
      201 => 'CREATED',
      204 => 'UPDATED',
      400 => 'BAD REQUEST',
      404 => 'NOT FOUND',
      405 => 'METHOD NOT ALLOWED',
      500 => 'INTERNAL SERVER ERROR'
      );
  header('Status: '.$status[$code]);
  if ($data) {
    return json_encode(array('status' => $code, 'message' => $message, 'data' => $data));
  } else {
    return json_encode(array('status' => $code, 'message' => $message));
  }
}


/**
 * Return name from email before @
 */
function removeEmail($text) {
  list($text) = explode('@', $text);
  $text = preg_replace('/[^a-z0-9]/i', ' ', $text);
  $text = ucwords($text);
  return $text;
}


/**
 * Generate custom length random token
 */
function genToken($length) {  
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}


/**
 * Get Movie Details
 * @param string $mId - tMDb id
 */
function getDataFromTmdb($mId) {
  $temp = dbDet("live");
  $tmdbKey = $temp[5];
  $curl = curl_init();
  // 'https://api.themoviedb.org/3/movie/{}/videos?api_key={}&language=en-US'
  $getMovie = "https://api.themoviedb.org/3/movie/" . $mId . "?api_key=" . $tmdbKey . "&language=en-US";
  curl_setopt_array($curl, array(
    CURLOPT_URL => $getMovie,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
       "Content-Type: application/json",
       "cache-control: no-cache"
    ),
  ));
  $response = curl_exec($curl);
  $data = json_decode($response, true);
  curl_close($curl);
  return json_response(200, "success", $data);
}

