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
  $new_data = array(
    "tmdb_id" => $data['id'],
    "title" => $data['title'], 
    "tagline" => $data['tagline'], 
    "release_date" => $data['release_date'], 
    "runtime" => $data['runtime'], 
    "genres" => $data['genres'],
    "overview" => $data['overview'], 
    "vote_average" => $data['vote_average'], 
    "poster_path" => $data['poster_path'],
    "backdrop_path" => $data['backdrop_path']
  );
  $copy_from = 'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $data['poster_path'];
  $paste_to = ROOT . '/p' . $data['poster_path'];
  copy($copy_from, $paste_to);
  return json_response(200, "success", $new_data);
}


/**
 * Create JWT Token
 * @param string $uuid 
 * @param string $name
 * @param string $pic
 * @return JSON Obj JWT
 */
function encodeJWT($uuid, $name, $pic) {
  $dbDet = dbDet("local");
  $private_key = $dbDet[6];
  $iss = $dbDet[7];
  $exp = time() + 60*60;
  $header = json_encode([
    'typ' => 'JWT',
    'alg' => 'HS256'
  ]);
  $payload = json_encode([
    'iss' => $iss,
    'exp' => $exp,
    'uuid' => $uuid,
    'name' => $name,
    'pic' => $pic
  ]);
  $base64UrlHeader = encode64Url($header);
  $base64UrlPayload = encode64Url($payload);
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $private_key, true);
  $base64UrlSignature = encode64Url($signature);
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
  return $jwt;
}


/**
 * Check JWT
 * @param JSON $encodedJWT
 * @return boolean
 */
function checkJWT($encodedJWT) {
  $dbDet = dbDet("live");
  $private_key = $dbDet[6];
  $parts = explode('.', $encodedJWT);
  if (count($parts) === 3) {
    $decodedHeader = base64_decode($parts[0]);
    $decodedPayload = base64_decode($parts[1]);
    
    $expiration = json_decode($decodedPayload)->exp;

    $base64UrlHeader = encode64Url($decodedHeader);
    $base64UrlPayload = encode64Url($decodedPayload);
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $private_key, true);
    $base64UrlSignature = encode64Url($signature);
    
    $signatureValid = ($base64UrlSignature === $parts[2]);

    if ($signatureValid) {
      if ($expiration > time()) {
        return array("JWT" => "VALID");
      } else {
        return array("JWT" => "INVALID");
      }
    } else {
      return array("JWT" => "INVALID");
    }
  } else {
    return array("JWT" => "INVALID");
  }
}


/**
 * Decode JWT
 * @param JSON $encodedJWT
 * @return boolean
 */
function decodeJWT($encodedJWT) {
  $parts = explode('.', $encodedJWT);
  $checkjwt = checkJWT($encodedJWT);
  if ($checkjwt) {
    // $decodedHeader = base64_decode($parts[0]);
    $decodedPayload  = base64_decode($parts[1]);
    return json_response(200, "success", json_decode($decodedPayload));
  } else {
    return json_response(405, "Get out of here");
  }
}


/**
 * Base 64 URL Encoder
 * @param string $decodedStr
 * @return base64 encoded string
 */
function encode64Url($decodedStr) {
  return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($decodedStr));
}