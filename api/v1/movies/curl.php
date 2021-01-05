<?php

include '../../../acc/funcs.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['mid'])) {
    $mId = $_POST['mid'];
    $res = getDataFromTmdb($mId);
    echo $res;
  }
}

