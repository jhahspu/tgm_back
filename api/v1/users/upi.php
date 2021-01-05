<?php

include_once '../../../acc/database.php';
include_once '../../../acc/users.php';
include_once '../../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['files'])) {
    $file_name = $_FILES['files']['name'][0];
    $file_tmp = $_FILES['files']['tmp_name'][0];
    $file_type = $_FILES['files']['type'][0];
    $file_size = $_FILES['files']['size'][0];
    $tmp = explode('.', $file_name);
    $file_ext = end($tmp);

    $path = ROOT . 'u/';
    $extensions = ['jpeg', 'jpg', 'png', 'webp'];

    $new_image_fn = genToken(12) . '.' . $file_ext;
    $new_image_name = $path . $new_image_fn;

    if (!in_array($file_ext, $extensions)) {
      echo json_response(405, "Files accepted: jpeg, jpg, png and webp");
    } else {
      if (isset($_POST['token'])) {
        $token = $_POST['token'];
        $database = new Database();
        $db = $database->connect();
        $usr = new User($db);
        $passed = $usr->changeUserAvatar($token, $new_image_fn);
        if ($passed) {
          move_uploaded_file($file_tmp, $new_image_name);
          echo json_response(200, "Avatar changed");
        } else {
          echo json_response(404, "Invalid token !");
        }
      }
    }
  } else {
    echo json_response(404, "no files");
  }
}