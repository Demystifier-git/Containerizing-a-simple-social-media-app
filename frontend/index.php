<?php
session_start();
require "actions.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"] ?? "");
  $passwordRaw = $_POST["password"] ?? "";

  if ($username !== "" && $passwordRaw !== "") {
    $password = hash("ripemd128", $passwordRaw);
    $mysql = connectToMySQL();
    $result = runSelectQuery($mysql, "SELECT id FROM users_social10 WHERE username = ?", "s", $username);

    if ($result->num_rows === 0) {
      runQuery($mysql, "INSERT INTO users_social10 (username, password, account_date) VALUES (?, ?, UTC_TIMESTAMP())", "ss", $username, $password);
      $result = runSelectQuery($mysql, "SELECT id FROM users_social10 WHERE username = ?", "s", $username);
      $_SESSION["userID"] = $result->fetch_assoc()["id"];
      $mysql->close();
      header("Location: postsFeed.php");
      exit;
    } else {
      $error = "User already exists. Please log in.";
    }
    $mysql->close();
  } else {
    $error = "Please fill in both fields.";
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
      <a href="index.php" class="active-link">Sign up</a> |
      <?php if (isset($_SESSION["userID"])) { ?>
        <a href="logout.php">Log out</a>
      <?php } else { ?>
        <a href="login.php">Log in</a>
      <?php } ?>
    </nav>
  </div>
  <div class="title--auth">
    <h2>Sign up</h2>
  </div>

  <?php if ($error): ?>
    <div class="warning">
      <em><?= htmlspecialchars($error) ?></em>
    </div>
  <?php endif; ?>

  <div class="signup-form">
    <form action="index.php" method="post">
      <div class="fields">
        <div class="input-field">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" required>
        </div>
        <div class="input-field">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required>
        </div>
      </div>
      <button type="submit">Sign up</button>
    </form>
  </div>

  <?php require "footer.php" ?>
  <script src="auth.js"></script>
</body>

</html>
