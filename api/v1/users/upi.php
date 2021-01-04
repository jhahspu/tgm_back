<?php

include_once '../../../acc/funcs.php';
// include_once '../../../acc/database.php';
// include_once '../../../acc/users.php';
include_once '../../../config.php';

// $database = new Database();
// $db = $database->connect();
// $usr = new User($db);
// $data = json_decode(file_get_contents('php://input', false));
// if (empty($data->token)) return json_response(400, "Token required");
// $result = $usr->checkToken($data->token);
// echo $result;
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

    if (!in_array($file_ext, $extensions)) {
      echo json_response(405, "Files accepted: jpeg, jpg, png and webp");
    } else {
      
      // TODO check TOKEN and if ok then save file and update user info
      
      $new_image_fn = genToken(12) . '.' . $file_ext;
      $new_image_name = $path . $new_image_fn;
      move_uploaded_file($file_tmp, $new_image_name);
      

      echo json_response(200, "received something ", array(
        'file' => $file_name,
        'filetmp' => $file_tmp,
        'type' => $file_type,
        'size' => $file_size,
        'ext' => $file_ext
      ));
    }

    
  } else {
    echo json_response(404, "no files");
  }
}





// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   if (isset($_FILES['files'])) {
//       $errors = [];
//       $path = 'u/';
//       $extensions = ['jpg', 'jpeg', 'png', 'gif'];

//       $all_files = count($_FILES['files']['tmp_name']);

//       for ($i = 0; $i < $all_files; $i++) {
//           $file_name = $_FILES['files']['name'][$i];
//           $file_tmp = $_FILES['files']['tmp_name'][$i];
//           $file_type = $_FILES['files']['type'][$i];
//           $file_size = $_FILES['files']['size'][$i];
//           $file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));

//           $file = $path . $file_name;

//           if (!in_array($file_ext, $extensions)) {
//               $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
//           }

//           if ($file_size > 2097152) {
//               $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
//           }

//           if (empty($errors)) {
//               move_uploaded_file($file_tmp, $file);
//           }
//       }

//       if ($errors) echo($errors);
//   }
// }