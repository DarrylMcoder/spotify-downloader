<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
//require('../vendor/autoload.php');
require('../src/SpotDL.php');
//require('../src/DownloadStreamer.php');
require('../src/YouTubeStreamer.php');
require('../src/VideoSaver.php');
//use \YouTube\DownloadStreamer;
use \YouTube\YouTubeStreamer;
use \YouTube\VideoSaver;
$spotdl = new SpotDL();/*
$name = $_GET['name'];
$url = $_GET['url'];
$img_url = $_GET['img_url'];
$preview_url = $_GET['preview_url'];
$downloads = 1;
$timestamp = time();
*/
$filename = $spotdl->download($url);
$downloader = new VideoSaver();
$downloader->setDownloadedFileName($filename);
$downloader->download('http://'.$_SERVER['HTTP_HOST'].'/'.$filename);

/*
include('./config.php');
$sql = "INSERT INTO songs(artist, name, url, img_url, preview_url, downloads, timestamp) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE downloads = downloads + 1, timestamp = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('sssssiii',$artist, $name, $url, $img_url, $preview_url, $downloads, $timestamp, $timestamp);
$stmt->execute();
echo $mysqli->error;*/