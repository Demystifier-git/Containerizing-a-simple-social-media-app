<?php
require "../actions.php";
$postID = (int) $_POST["postID"];
$mysql = connectToMySQL();
$sql = "
  select * from posts_social10
  where id = ?
";
$result = runSelectQuery($mysql, $sql, "i", $postID);
$doesPostExist = $result->num_rows ? true : false;

if ($doesPostExist) {
  exit(json_encode(["doesPostExist" => $doesPostExist]));
}

exit(json_encode(["doesPostExist" => $doesPostExist]));
