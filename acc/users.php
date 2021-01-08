<?php


include_once 'funcs.php';


class User {


  private $conn;


  public function __construct($db) {
    $this->conn = $db;
  }

  
  /**
   * Check user email & password in DB and return user infos
   * @param string $um user email
   * @param string $up user password
   * @return JSON: status code, message, data{"name", "pic", "uuid"}
   */
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

  
  /**
   * Check if provided token in DB
   * @param string $tk - user token
   * @return boolean
   */
  public function checkToken($tk) {
    $query = "SELECT name, pic, uuid FROM users WHERE uuid = :uuid";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':uuid', $tk);
    $stmt->execute();
    $token = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($token) {
      return true;
    } else {
      return false;
    }
  }

  
  /**
   * Register User
   * @param string $um - user email
   * @param string $up - user password
   * @return JSON with new user infos
   */
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
      $token = genToken(8) . '-' . genToken(4) . '-' . genToken(4) . '-' . genToken(4) . '-' . genToken(8);
      $un = removeEmail($um) . "_" . genToken(6);
      $query = "INSERT INTO users (email, password, name, pic, uuid) VALUES (:email, :password, :name, :pic, :uuid)";
      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':email', $um);
      $stmt->bindValue(':password', $passwordHash);
      $stmt->bindValue(':name', $un);
      $stmt->bindValue(':pic', 'np.webp');
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


  /**
   * Change user password if old pass and token are valid
   * @param string $tk - user uuid
   * @param string $op - old password
   * @param string $np - new password
   * @return status code and message
   */
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


  /**
   * Delete user if token and password match with DB indo
   * @param string $tk - user uuid
   * @param string $up - user password
   * @return status code and message
   */
  public function removeUser($tk, $up) {
    $query = "SELECT * FROM users WHERE uuid = :uuid";
    $stmt = $this->conn->prepare($query);
    $stmt->bindValue(':uuid', $tk);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user){
      return json_response(404, "User not found");
    } else {
      $validPass = password_verify($up, $user['password']);
      if ($validPass) {
          $query = "DELETE FROM users WHERE uuid = :uuid";
          $stmt = $this->conn->prepare($query);
          $stmt->bindValue(':uuid', $tk);
          $stmt->execute();
          return json_response(200, "User removed");
      } else {
        return json_response(400, "Check password and try again");
      }
    }
  }


  /**
   * Change user avatar if token passed
   * @param string $tk - user token
   * @param string $na - new avatar name
   * @return Obj containing updated infos about user
   */
  public function changeUserAvatar($tk, $na) {
    $passed = $this->checkToken($tk);
    if ($passed) {
      $query = "UPDATE users SET pic = :pic WHERE uuid = :uuid";
      $stmt = $this->conn->prepare($query);
      $stmt->bindValue(':pic', $na);
      $stmt->bindValue(':uuid', $tk);
      $stmt->execute();
      return true;
    } else {
      return false;
    }
  }
}
