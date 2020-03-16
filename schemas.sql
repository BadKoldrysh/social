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
