<?php

require 'funcs.php';

class Mvs {

  
  private $conn;


  public function __construct($db) {
    $this->conn = $db;
  }

  
  public function getRnd($gen) {
    if(empty($gen) || $gen == 'any') {
      $condition = "";
    } else {
      $condition = " WHERE genres like '%" . $gen . "%'";
    }
    $query = "SELECT * FROM movies ".$condition." ORDER BY RAND() LIMIT 3";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS);
    if($result) {
      return json_response(200, "success", $result);
    } else {
      return json_response(404, "No titles found");
    }
  }

  
  public function getLtst() {
    $query = "SELECT * FROM movies ORDER BY id DESC LIMIT 3";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS);
    if($result) {
      return json_response(200, "success", $result);
    } else {
      return json_response(404, "No titles found");
    }
  }

}