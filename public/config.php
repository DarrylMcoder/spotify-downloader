<?PHP
    
$db = parse_url(getenv('JAWSDB_URL'));
$mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['path']);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}