<?PHP
    
require('../src/SpotDL.php');
$spotdl = new SpotDL();

$url = $_GET['url'];
if(!isset($url)){
  die("No URL");
}
$filename = $spotdl->download($url);
header("Location: download3.php?filename=" . $filename);