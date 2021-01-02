<?php

require 'funcs.php';

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
    if(!$user){
      return json_response(404, "Email not found");
    } else {
      $validPass = password_verify($up, $user['password']);
      if ($validPass) {
          return json_response(200, "Sign in successful", array(
            "name" => $user['name'],
            "pic" => $user['pic'],
            "uuid" => $user['uuid']
          ));
      } else {
        return json_response(400, "Check password and try again");
      }
    }
  }

  
  public function checkToken($tk) {
    $query = "SELECT name, pic, uuid FROM users WHERE uuid = :uuid";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':uuid', $tk);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
      return json_response(200, "Found you!", array(
        "name" => $user['name'],
        "pic" => $user['pic']
      ));
    } else {
      return json_response(404, "Invalid token ");
    }
  }

  
  public function registerUser($um, $up) {
    $query = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':email', $um);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user['num'] > 0){
      return json_response(400, "Email already in use");
    } else {
      $passwordHash = password_hash($up, PASSWORD_BCRYPT, array("cost" => 12));
      $token = genToken(32);
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
        return json_response(200, "Registration successful", array(
          "name" => $un,
          "uuid" => $token
        ));
      }
    }
  }


  public function changePassword($tk, $op, $np) {
    $query = "SELECT * FROM users WHERE uuid = :uuid";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':uuid', $tk);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user){
      return json_response(404, "User not found");
    } else {

      $validPass = password_verify($op, $user['password']);
      if ($validPass) {
          $passwordHash = password_hash($np, PASSWORD_BCRYPT, array("cost" => 12));
          $query = "UPDATE users SET password = :password WHERE uuid = :uuid";
          $stmt = $this->conn->prepare($query);
          $stmt->bindValue(':password', $passwordHash);
          $stmt->bindValue(':uuid', $tk);
          $stmt->execute();
          return json_response(200, "Password change successful");
      } else {
        return json_response(400, "Check password and try again");
      }
    }
  }
}
