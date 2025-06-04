<?php
session_start();
require "actions.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["username"], $_POST["password"])) {
  $username = $_POST["username"];
  $password = hash("ripemd128", $_POST["password"]);

  $mysql = connectToMySQL();
  $result = runSelectQuery($mysql, "select * from users_social10 where username = ? and password = ?", "ss", $username, $password);
  $mysql->close();

  if ($result->num_rows === 1) {
    $_SESSION["userID"] = $result->fetch_assoc()["id"];
    header("Location: postsFeed.php");
    exit();
  } else {
    $error = "Incorrect login credentials";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require "page/head.php" ?>
  <style>
    @import url(css/navLinks.css);
    @import url(css/titles.css);
    @import url(css/form.css);
    @import url(css/footer.css);
  </style>
</head>

<body>
  <div class="title--social10">
    <h1>Social10</h1>
  </div>
  <div class="nav-links">
    <nav>
      <a href="postsFeed.php">Posts</a> |
      <a href="index.php">Sign up</a> |
      <?php if (isset($_SESSION["userID"])) { ?>
        <a href="logout.php">Log out</a>
      <?php } else { ?>
        <a href="login.php" class="active-link">Log in</a>
      <?php } ?>
    </nav>
  </div>
  <div class="title--auth">
    <h2>Log in</h2>
  </div>

  <?php if (!empty($error)): ?>
    <div class="warning">
      <em><?= $error ?></em>
    </div>
  <?php endif; ?>

  <div class="signup-form">
    <form action="login.php" method="post">
      <div class="fields">
        <div class="input-field">
          <label for="">Username</label>
          <input type="text" name="username" id="username" required>
        </div>
        <div class="input-field">
          <label for="">Password</label>
          <input type="password" name="password" id="password" required>
        </div>
      </div>
      <button type="submit">Log in</button>
    </form>
  </div>

  <?php require "footer.php" ?>
  <script src="auth.js"></script>
</body>
</html>
