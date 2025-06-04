<?php
session_start();
require "actions.php";

if (isset($_SESSION["userID"])) {
  $userID = (int) $_SESSION["userID"];
}

$postID = isset($_GET["id"]) ? (int) $_GET["id"] : null;

if ($postID) {
  $sql = "
    select posts_social10.id as post_id, username, content, post_date
    from  users_social10
    inner join posts_social10
    on  users_social10.id = posts_social10.author_id
    where posts_social10.id = ?
  ";
  $mysql = connectToMySQL();
  $result = runSelectQuery($mysql, $sql, "i", $postID);
  $post = $result->num_rows ? $result->fetch_assoc() : null;

  if ($post) {
    if (isset($_SESSION["userID"]) && isset($_POST["comment"])) {
      $comment = $_POST["comment"];
      // comment
      $sql = "
        insert into comments_social10 (commenter_id, post_id, comment, comment_date)
        values (?, ?, ?, utc_timestamp())
      ";
      $affectedRow = runQuery($mysql, $sql, "iis", $userID, $postID, $comment);
      if ($affectedRow > 0) {
        echo "<script>
                alert('Comment posted');
                location.href = 'post.php?id={$postID}';
              </script>";
        exit();
      }
    }

    // fetch likes for the post
    $sql = "
      select count(id) as total_likes
      from likes_social10
      where post_id = ? 
    ";
    $result = runSelectQuery($mysql, $sql, "i", $postID);
    $totalLikes = $result->fetch_assoc()["total_likes"];

    // check if user liked the post
    $haveLiked = false;
    if (isset($_SESSION["userID"])) {
      $sql = "
        select *
        from likes_social10
        where post_id = ? 
        and liker_id = ?
      ";
      $result = runSelectQuery($mysql, $sql, "ii", (int)$post["post_id"], (int)$userID);
      $haveLiked = $result->num_rows ? true : false;
    }

    // fetch comments count
    $sql = "
      select count(id) as total_comments
      from comments_social10
      where post_id = ? 
    ";
    $result = runSelectQuery($mysql, $sql, "i", (int)$post["post_id"]);
    $totalComments = $result->fetch_assoc()["total_comments"];

    // fetch comments
    $sql = "
      select users_social10.username as username, comment, comment_date, post_id
      from users_social10
      inner join comments_social10
      on users_social10.id = comments_social10.commenter_id
      where post_id = ?
      order by comment_date desc
    ";
    $result = runSelectQuery($mysql, $sql, "i", $postID);
    $comments = $result->num_rows ? $result->fetch_all(MYSQLI_ASSOC) : null;

  } else {
    $mysql->close();
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
    @import url(css/posts.css);
    @import url(css/comments.css);
    @import url(css/footer.css);

    body {
      display: flex;
      flex-direction: column;
      overflow: hidden;
      height: 100dvh;
    }
  </style>
</head>

<body>
  <div class="title--social10">
    <h1>Social10</h1>
  </div>
  <div class="nav-links nav-links--single">
    <nav>
      <a href="postsFeed.php">Posts</a> |
      <?php if (isset($_SESSION["userID"])) { ?>
        <a href="logout.php">Log out</a>
      <?php } else { ?>
        <a href="index.php">Sign up</a> |
        <a href="login.php">Log in</a>
      <?php } ?>
    </nav>
  </div>
  <div class="title--posts title--posts--single">
    <h2>Post</h2>
  </div>

  <?php if (!$postID) { ?>
    <div class="title--no-posts-message">
      <h3>No post selected</h3>
    </div>
  <?php
  } else if (!$post) {
  ?>
    <div class="title--no-posts-message">
      <h3>Post not found</h3>
    </div>
  <?php
  } else {
  ?>
    <div class="post post-single">
      <div class="post-head">
        <h3><?= htmlspecialchars($post["username"]) ?>'s post</h3>
        <small><?= htmlspecialchars($post["post_date"]) ?></small>
      </div>
      <div class="post-content">
        <p><?= htmlspecialchars($post["content"]) ?></p>
      </div>
      <div class="engagements-group">
        <div class="engagements">
          <button class="likeButton" data-post-Id="<?= $postID ?>">
            <span id="likeCounter<?= $postID ?>"><?= $totalLikes ?></span>
            <?php if (isset($_SESSION["userID"])) {
              if ($haveLiked) { ?>
                <i class="material-icons" style="color: red;" id="likeButton<?= (int)$post["post_id"] ?>">favorite</i>
              <?php } else { ?>
                <i class="material-icons" id="likeButton<?= (int)$post["post_id"] ?>">favorite_outline</i>
              <?php }
            } else { ?>
              <i class="material-icons">favorite_outline</i>
            <?php } ?>
          </button>
        </div>
        <div class="engagements">
          <button data-post-id="<?= (int)$post["post_id"] ?>" class="commentButton">
            <span><?= $totalComments ?></span>
            <i class="material-icons">comment</i>
          </button>
        </div>
      </div>

      <?php if (isset($_SESSION["userID"])) { ?>
        <div class="comment-form">
          <form action="post.php?id=<?= $postID ?>" method="post" data-post-Id="<?= (int)$post["post_id"] ?>">
            <input type="text" name="comment" placeholder="Drop a comment" id="commentInput<?= (int)$post["post_id"] ?>">
            <input type="hidden" name="postID" value="<?= (int)$post["post_id"] ?>">
            <button type="submit">Comment</button>
          </form>
        </div>
      <?php } ?>
    </div>

    <div class="title--comments">
      <h3>Comments</h3>
    </div>
    <div class="comments">
      <?php
      if ($comments) {
        foreach ($comments as $comment) {
      ?>
          <div class="comment">
            <h3><?= htmlspecialchars($comment["username"]) ?></h3>
            <small><?= htmlspecialchars($comment["comment_date"]) ?></small>
            <p><?= htmlspecialchars($comment["comment"]) ?></p>
          </div>
      <?php
        }
      }
      ?>
    </div>
<?php
    $mysql->close();
  }
?>
  <?php require "footer.php" ?>
  <script src="like.js"></script>
  <script src="comment.js"></script>
  <script src="time--single-post.js"></script>
</body>

</html>
