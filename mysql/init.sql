CREATE USER 'mysql_user'@'%' IDENTIFIED BY 'mysql_pass';
GRANT ALL PRIVILEGES ON *.* TO 'mysql_user'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE app;
USE app;

CREATE TABLE Post(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(20),
    content TEXT
);


CREATE TABLE Image(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    image_data VARBINARY(MAX) NOT NULL
);

CREATE TABLE Comment(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(255),
    creation_date DATE NOT NULL default current_timestamp ON UPDATE current_timestamp 

);

CREATE TABLE Post(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(999) NOT NULL,
    image INT FOREIGN KEY REFERENCES Image(id),
    creation_date DATE NOT NULL default current_timestamp,
    comments INT FOREIGN KEY REFERENCES Comment(id)
);

CREATE TABLE Profile(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    profile_picture INT FOREIGN KEY REFERENCES Image(id) NOT NULL,
    description VARCHAR(100) NOT NULL,
    
);

CREATE TABLE Follow(
    follower INT,
    followed INT,
    PRIMARY KEY, 
    
);

CREATE TABLE User(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL, 
    password CHAR(40) NOT NULL, -- hashed with SHA-1
    profile INT FOREIGN KEY REFERENCES Profile(id) NOT NULL,
);


