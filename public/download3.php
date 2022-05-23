<?PHP
    
require('../src/YouTubeStreamer.php');
require('../src/VideoSaver.php');
use \YouTube\YouTubeStreamer;
use \YouTube\VideoSaver;
$filename = $_GET['filename'];
if(!isset($filename)){
  die("No file name.");
}

$downloader = new VideoSaver();
$downloader->setDownloadedFileName($filename);
$downloader->download('http://'.$_SERVER['HTTP_HOST'].'/'.$filename);