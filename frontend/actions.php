<?php
// functions to help me with database relations

/**
 * @param mysqli $mysql
 * @param string $sql
 */
function runQuery($mysql, $sql, $format = null, ...$params)
{
  $stmt = $mysql->prepare($sql);
  if ($mysql->error) exit($mysql->error . " | " . __LINE__);
  if ($format):
    $stmt->bind_param($format, ...$params);
    if ($stmt->error) exit($stmt->error); // Only exit if error exists
  endif;
  $stmt->execute();
  if ($stmt->error) exit($stmt->error . " | " . __LINE__);
  $affectedRow = $stmt->affected_rows;
  $stmt->close();
  return $affectedRow;
}


/**
 * @param mysqli $mysql
 * @param string $sql
 * @param string $format
 * @param string ...$params
 */
function runSelectQuery($mysql, $sql, $format = null, ...$params)
{
  $stmt = $mysql->prepare($sql);
  if ($mysql->error) exit($mysql->error);
  if ($format):
    $stmt->bind_param($format, ...$params);
    if ($stmt->error) exit($stmt->error); // Only exit if error exists
  endif;
  $stmt->execute();
  if ($stmt->error) exit($stmt->error);
  $result = $stmt->get_result();
  $stmt->close();
  return $result;
}

/**
 * @param string $host
 * @param string $user
 * @param string $passwd
 * @param string $db
 */
function connectToMySQL($host = "mydb-instance.c69ouswgucms.us-east-1.rds.amazonaws.com", $user = "myuser", $passwd = "myuserpassword", $db = "social10")
{
  $mysql = new mysqli($host, $user, $passwd, $db);
  if ($mysql->connect_error) exit($mysql->connect_error);
  return $mysql;
}
