<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);

//require('../vendor/autoload.php');
require('../src/SpotDL.php');
$spotdl = new SpotDL();
$url = $_GET['url'];
$filename = $spotdl->download($url);
header("Content-disposition: attachment; filename=$filename");
header("Content-type: audio/mpeg");
$fp = fopen($filename,'rb');
while (!feof($fp)) {
  echo fread($fp, 8192);
  flush();
}
fclose($fp);