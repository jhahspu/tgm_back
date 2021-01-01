<?php

class User {

  private $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function checkUser($um, $up) {
    $query = "SELECT id, email, password, uuid FROM users WHERE email = :email";
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
      $query = "INSERT INTO users (email, password, uuid) VALUES (:email, :password, :uuid)";
      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':email', $um);
      $stmt->bindValue(':password', $passwordHash);
      $stmt->bindValue(':uuid', $token);
      $result = $stmt->execute();
      if($result){
        return json_encode(array(
          "status" => "success",
          "message" => "registration successful",
          "uuid" => $token
        ));
      }
    }
  }
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