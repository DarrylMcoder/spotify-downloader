<?PHP
    
include('./config.php');
$sql = "CREATE TABLE songs(
id INT AUTO_INCREMENT PRIMARY KEY,
artist VARCHAR(255),
name VARCHAR(255) UNIQUE,
url VARCHAR(255),
img_url VARCHAR(255),
preview_url VARCHAR(255),
downloads INT,
timestamp BIGINT
)";
$mysqli->query($sql);