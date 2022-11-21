CREATE USER 'mysql_user'@'%' IDENTIFIED BY 'mysql_pass';
GRANT ALL PRIVILEGES ON *.* TO 'mysql_user'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE app;
USE app;

CREATE TABLE post(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(20),
    content TEXT
);

INSERT INTO post
    VALUES
    (null,"Elon Musk", "The CyberTruck is here! #CyberTruck"),
    (null,"Tory Bruno", "ULA will be launching atlas V this week, get your popcorn ready #ULA #AtlasV #Space"),
    (null,"Freya Homer","SPLINES ARE THE HORROR!!!");
    
