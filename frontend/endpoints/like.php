<?php
require "../actions.php";
session_start();
$userID = (int) $_SESSION["userID"];
$postID = (int) $_POST["postID"];
$mysql = connectToMySQL();
$sql = "
  select * from likes_social10
  where liker_id = ?
  and post_id = ?
";
$result = runSelectQuery($mysql, $sql, "ii", $userID, $postID);
if ($result->num_rows) {
  $sql = "
  delete from likes_social10
  where liker_id = ?
  and post_id = ?
  ";
  runQuery($mysql, $sql, "ii", $userID, $postID);
  $sql = "
  select count(*) as total_likes from likes_social10
  where post_id = ?
  ";
  $result = runSelectQuery($mysql, $sql, "i", $postID);
  $totalLikes = $result->fetch_assoc()["total_likes"] ?? 0;
  echo json_encode(["haveLiked" => false, "totalLikes" => $totalLikes]);
} else {
  $sql = "
  insert into likes_social10 (liker_id, post_id)
  values (?, ?)
  ";
  runQuery($mysql, $sql, "ii", $userID, $postID);
  $sql = "
  select count(*) as total_likes from likes_social10
  where post_id = ?
";
  $result = runSelectQuery($mysql, $sql, "i", $postID);
  $totalLikes = $result->fetch_assoc()["total_likes"] ?? 0;
  echo json_encode(["haveLiked" => true, "totalLikes" => $totalLikes]);
}
$mysql->close();
