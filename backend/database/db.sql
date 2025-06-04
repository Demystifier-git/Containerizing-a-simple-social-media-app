-- USE social10;

-- DROP DATABASE social10;

-- DROP TABLE users;
-- DROP TABLE posts;
-- DROP TABLE comments;
-- DROP TABLE likes;

-- CREATE DATABASE IF NOT EXISTS social10;

-- USE social10;

-- users table
CREATE TABLE IF NOT EXISTS users_social10 (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  account_date DATETIME NOT NULL,
  UNIQUE (username),
  CHECK (username != "" AND password != "" AND account_date != "")
);

-- posts table
CREATE TABLE IF NOT EXISTS posts_social10 (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  author_id INT NOT NULL,
  content VARCHAR(255) NOT NULL,
  post_date DATETIME NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users_social10 (id),
  CHECK (content != "" AND post_date != "")
);

-- comments table
CREATE TABLE IF NOT EXISTS comments_social10 (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  commenter_id INT NOT NULL,
  post_id INT NOT NULL,
  comment VARCHAR(255) NOT NULL,
  comment_date DATETIME NOT NULL,
  FOREIGN KEY (commenter_id) REFERENCES users_social10 (id),
  FOREIGN KEY (post_id) REFERENCES posts_social10 (id),
  CHECK (comment != "" AND comment_date != "")
);

-- likes table
CREATE TABLE IF NOT EXISTS likes_social10 (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  liker_id INT NOT NULL,
  post_id INT NOT NULL,
  FOREIGN KEY (liker_id) REFERENCES users_social10 (id),
  FOREIGN KEY (post_id) REFERENCES posts_social10 (id)
)