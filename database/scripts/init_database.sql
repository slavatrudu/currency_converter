CREATE DATABASE IF NOT EXISTS curr_conv;
CREATE USER IF NOT EXISTS 'curr_conv'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON `curr_conv`.* TO 'curr_conv'@'localhost';
