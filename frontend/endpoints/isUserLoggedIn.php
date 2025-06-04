<?php
session_start();
if (!isset($_SESSION["userID"])) {
  echo json_encode(["isUserLoggedIn" => false]);
  exit;
}
echo json_encode(["isUserLoggedIn" => true]);
