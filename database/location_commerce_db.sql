create database location_commerce_db;

use location_commerce_db;

CREATE TABLE USER
(
	user_id INT(8) NOT NULL AUTO_INCREMENT,
	username VARCHAR(30) NOT NULL,
	password VARCHAR(30) NOT NULL,
	email VARCHAR(30) NOT NULL,
    full_name VARCHAR (50) NOT NULL,
	phone VARCHAR(20) NOT NULL,
    address VARCHAR(30) NOT NULL,
    about_me VARCHAR(255) NOT NULL,
    latitude decimal(9, 6) NOT NULL,
    longitude decimal(9, 6) NOT NULL,
    chat_access DATE NOT NULL,
	PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE POST
(
	post_id INT(8) NOT NULL AUTO_INCREMENT,
    image LONGBLOB NOT NULL,
	username VARCHAR(30) NOT NULL,
    title VARCHAR(30) NOT NULL,
    description VARCHAR(500) NOT NULL,
    price INT(10) NOT NULL,
    category VARCHAR(30) NOT NULL,
    latitude decimal(9, 6) NOT NULL,
    longitude decimal(9, 6) NOT NULL,
    PRIMARY KEY (post_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
