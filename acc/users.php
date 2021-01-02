<?php

class User {

  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function checkUser($um, $up) {
    $query = "SELECT id, email, password, name, pic, uuid FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':email', $um);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user === false){
      return json_encode(array(
        "status" => "error",
        "message" => "email not found"
      ));
    } else {
      $validPass = password_verify($up, $user['password']);
      if ($validPass) {
        return json_encode(array(
          "status" => "success",
          "message" => "login successful",
          "name" => $user['name'],
          "pic" => $user['pic'],
          "uuid" => $user['uuid']
        ));
      } else {
        return json_encode(array(
          "status" => "error",
          "message" => "wrong password"
        ));
      }
    }
  }

  
  public function checkToken($tk) {
    // TODO
    // Should return an OK for when trying to insert into DB

  }

  
  public function registerUser($um, $up) {
    $query = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':email', $um);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row['num'] > 0){
      return json_encode(array(
        "status" => "error",
        "message" => "email already in use"
      ));
    } else {
      $passwordHash = password_hash($up, PASSWORD_BCRYPT, array("cost" => 12));
      $token = genToken(8) . "-" . genToken(4) . "-" . genToken(4) . "-" . genToken(8);
      // $un = removeEmail($um);
      $un = removeEmail($um) . "_" . genToken(6);
      $query = "INSERT INTO users (email, password, name, pic, uuid) VALUES (:email, :password, :name, :pic, :uuid)";
      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':email', $um);
      $stmt->bindValue(':password', $passwordHash);
      $stmt->bindValue(':name', $un);
      $stmt->bindValue(':pic', 'np.jpg');
      $stmt->bindValue(':uuid', $token);
      $result = $stmt->execute();
      if($result){
        return json_encode(array(
          "status" => "success",
          "message" => "registration successful",
          "name" => $un,
          "uuid" => $token
        ));
      }
    }
  }
}

function removeEmail($text) {
  list($text) = explode('@', $text);
  $text = preg_replace('/[^a-z0-9]/i', ' ', $text);
  $text = ucwords($text);
  return $text;
}

function genToken($length) {  
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}