<?php

include 'funcs.php';

class Mvs {

  
  private $conn;


  public function __construct($db) {
    $this->conn = $db;
  }


  /**
   * Return titles based on genre
   * @param string $gen - genre
   * @return Obj and status code with message 
   */
  public function getRnd($gen) {
    if(empty($gen) || $gen == 'any') {
      $condition = "";
    } else {
      $condition = " WHERE genres like '%" . $gen . "%'";
    }
    $query = "SELECT * FROM movies ".$condition." ORDER BY RAND() LIMIT 24";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS);
    if($result) {
      return json_response(200, "success", $result);
    } else {
      return json_response(404, "No titles found");
    }
  }


  /**
   * Return latest entris from DB
   */
  public function getLtst() {
    $query = "SELECT * FROM movies ORDER BY id DESC LIMIT 24";
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