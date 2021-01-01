<?php

class Mvs {

  private $conn;

  public $id;
  public $tmdb_id;
  public $title;
  public $tagline;
  public $release_date;
  public $runtime;
  public $genres;
  public $overview;
  public $poster;
  public $backdrop;
  public $trailers;

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
      return json_encode(array(
        "status" => "success",
        "data" => $result
      ));
    } else {
      return json_encode(array(
        "status" => "error",
        "message" => "no titles found"
      ));
    }
  }

  
  public function getLtst() {
    $query = "SELECT * FROM movies ORDER BY id DESC LIMIT 3";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS);
    if($result) {
      return json_encode(array(
        "status" => "success",
        "data" => $result
      ));
    } else {
      return json_encode(array(
        "status" => "error",
        "message" => "no titles found"
      ));
    }
  }

}