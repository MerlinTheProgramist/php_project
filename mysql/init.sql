
CREATE USER 'mysql_user'@'%' IDENTIFIED BY 'mysql_pass';
GRANT ALL PRIVILEGES ON *.* TO 'mysql_user'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE app;
USE app;

CREATE TABLE Image(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    
    image_path VARCHAR(255) NOT NULL
);

INSERT INTO Image (id,image_path) VALUES (1,"default_profile.png");



CREATE TABLE Profile(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    
    username VARCHAR(20) NOT NULL,
    profile_picture_id INT NOT NULL default 1,
    profile_desc VARCHAR(100) NOT NULL default "",
    
    FOREIGN KEY (profile_picture_id) REFERENCES Image(id)
);

CREATE TABLE Post(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    
    author_id INT,
    content VARCHAR(255) NOT NULL,
    image_id INT,
    creation_date TIMESTAMP NOT NULL default CURRENT_TIMESTAMP,

    FOREIGN KEY (author_id) REFERENCES Profile(id),
    FOREIGN KEY (image_id) REFERENCES Image(id)
);

CREATE TABLE Comment(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,

    post_id INT,
    content VARCHAR(255),
    creation_date TIMESTAMP NOT NULL default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- update automatic data on create and update 

    FOREIGN KEY (post_id) REFERENCES Post(id)
);


CREATE TABLE Follow(
    follower_id INT,
    followed_id INT,

    CONSTRAINT id PRIMARY KEY (follower_id,followed_id),
    FOREIGN KEY (follower_id) REFERENCES Profile(id),
    FOREIGN KEY (followed_id) REFERENCES Profile(id)
);

CREATE TABLE User(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    
    email VARCHAR(20) NOT NULL,
    username VARCHAR(20) NOT NULL, 
    password CHAR(40) NOT NULL, -- hashed with SHA-1
    profile_id INT NOT NULL,

    FOREIGN KEY (profile_id) REFERENCES Profile(id)
);

CREATE TABLE Heart(
    profile_id INT,
    post_id INT,

    FOREIGN KEY (profile_id) REFERENCES Profile(id),
    FOREIGN KEY (post_id) REFERENCES Post(id),
    PRIMARY KEY (profile_id,post_id)
);

DELIMITER $$

CREATE PROCEDURE newUser(IN email VARCHAR(20),IN username VARCHAR(20),IN password CHAR(40))
BEGIN
    INSERT INTO Profile(username) VALUES (username);
    INSERT INTO User(email, username, password, profile_id) VALUES (email,username,password, LAST_INSERT_ID());
END $$

DELIMITER ;

CALL newUser("email","nazwauzytkownika","haslo");
CALL newUser("Merlin@proton.me","Merlin","haslo");
CALL newUser("cos@onet.pl","K4mil","haslo");
CALL newUser("Jakis@cos.pl","Hubercik","haslo");
