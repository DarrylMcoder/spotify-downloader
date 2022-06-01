<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
require('../src/SpotDL.php');
require('../src/YouTubeStreamer.php');
require('../src/VideoSaver.php');
use \YouTube\YouTubeStreamer;
use \YouTube\VideoSaver;

$artist = '';
$name = $_GET['name'];
$url = $_GET['url'];
$img_url = $_GET['img_url'];
$preview_url = $_GET['preview_url'];
$downloads = 1;
$timestamp = time();

$spotdl = new SpotDL();
$filename = $spotdl->download($url);


$url = 'http://spotdl.darrylmcoder.com/'.$filename;
$downloader = new VideoSaver();
$downloader->setDownloadedFileName($filename);
$downloader->download($url);

include('./config.php');
$sql = "INSERT INTO songs(artist, name, url, img_url, preview_url, downloads, timestamp) VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE downloads = downloads + 1, timestamp = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('sssssiii',$artist, $name, $url, $img_url, $preview_url, $downloads, $timestamp, $timestamp);
$stmt->execute();
//echo $mysqli->error;