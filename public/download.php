<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);

//require('../vendor/autoload.php');
require('../src/SpotDL.php');
$spotdl = new SpotDL();
$name = $_GET['name'];
$url = $_GET['url'];
$img_url = $_GET['img_url'];
$preview_url = $_GET['preview_url'];
$downloads = 1;
$timestamp = time();

$filename = $spotdl->download($url);
header("Location: http://yt.app.darrylmcoder.com/download.php?n=$filename&url=http://spotdl.darrylmcoder.com/$filename");
flush();
include('./config.php');
$sql = "INSERT INTO songs(artist, name, url, img_url, preview_url, downloads, timestamp) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE downloads = downloads + 1, timestamp = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_params('sssssiii',$artist, $name, $url, $img_url, $preview_url, $downloads, $timestamp, $timestamp);
$stmt->execute();
echo $mysqli->error;