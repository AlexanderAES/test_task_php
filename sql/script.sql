CREATE SCHEMA only_data ;

CREATE TABLE ONLY_USER (
                           id INT PRIMARY KEY auto_increment,
                           user_name VARCHAR(50),
                           phone VARCHAR(11),
                           email VARCHAR(50),
                           password VARCHAR(255),
);