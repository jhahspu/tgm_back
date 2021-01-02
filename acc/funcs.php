<?php

// RETURN JSON RESPONSE WITH HEADERS
function json_response($code = 200, $message = null, $data = null) {
  header_remove();
  http_response_code($code);
  header('Access-Control-Allow-Origin: *');
  header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
  header('Content-Type: application/json');
  $status = array(
    // SUCCESS
      200 => 'OK',
      201 => 'CREATED',
      204 => 'UPDATED',
    // CLIENT SIDE ERROR
      400 => 'BAD REQUEST',
      404 => 'NOT FOUND',
      405 => 'METHOD NOT ALLOWED',
    // 5xx SERVER ERROR
      500 => 'INTERNAL SERVER ERROR'
      );
  header('Status: '.$status[$code]);
  if ($data) {
    return json_encode(array('status' => $code, 'message' => $message, 'data' => $data));
  } else {
    return json_encode(array('status' => $code, 'message' => $message));
  }
}


// RETURN STRING BEFORE '@...'
function removeEmail($text) {
  list($text) = explode('@', $text);
  $text = preg_replace('/[^a-z0-9]/i', ' ', $text);
  $text = ucwords($text);
  return $text;
}


// GENERATE A RANDOM TOKEN WITH ANY LENGTH
function genToken($length) {  
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
