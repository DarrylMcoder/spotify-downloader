<?PHP
    
set_time_limit(0);
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
require('../src/YouTubeStreamer.php');
require('../src/VideoSaver.php');
use \YouTube\YouTubeStreamer;
use \YouTube\VideoSaver;
$filename = $_GET['filename'];
if(!isset($filename)){
  die("No file name.");
}

$url = 'http://spotdl.darrylmcoder.com/'.$filename;
$downloader = new VideoSaver();
$downloader->setDownloadedFileName($filename);
$downloader->download($url);