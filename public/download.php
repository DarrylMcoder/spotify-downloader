<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);

//require('../vendor/autoload.php');
require('../src/SpotDL.php');
$spotdl = new SpotDL();
$url = $_GET['url'];
$filename = $spotdl->download($url);
header('Location: http://yt.app.darrylmcoder.com/download.php?n='.$filename.'&url=http://spotdl.darrylmcoder.com/'.$filename);