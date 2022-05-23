<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
require('../src/SpotDL.php');
$spotdl = new SpotDL();

$url = $_GET['url'];
if(!isset($url)){
  die("No URL");
}
$filename = $spotdl->download($url);
header("Location: download3.php?filename=" . $filename);