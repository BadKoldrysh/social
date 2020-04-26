CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(25),
    last_name VARCHAR(25),
    email VARCHAR(100),
    password VARCHAR(255),
    signup_date DATE,
    profile_pic VARCHAR(255),
    num_posts INT,
    num_likes INT,
    user_closed VARCHAR(3),
    friend_array TEXT
);

CREATE TABLE posts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    body TEXT,
    added_by VARCHAR(60),
    user_to VARCHAR(60),
    date_added DATETIME,
    user_closed VARCHAR(3),
    deleted VARCHAR(3),
    likes INT
);

CREATE TABLE post_comments(
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_body TEXT,
    posted_by VARCHAR(60),
    posted_to VARCHAR(60),
    date_added DATETIME,
    removed VARCHAR(3),
    post_id INT
);

CREATE TABLE likes(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60),
    post_id INT
);

CREATE TABLE friend_requests(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_to VARCHAR(60),
    user_from VARCHAR(60)
);

CREATE TABLE messages(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_to VARCHAR(60),
    user_from VARCHAR(60),
    body TEXT,
    date DATETIME,
    opened VARCHAR(3),
    viewed VARCHAR(3),
    deleted VARCHAR(3)
);
