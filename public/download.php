<?PHP
    
ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);

//require('../vendor/autoload.php');
require('../src/SpotDL.php');
$spotdl = new SpotDL();
$url = $_GET['url'];
echo '<!doctype html>';
echo '<head>';
flush();
$filename = $spotdl->download($url);
echo '<meta charset="utf-8">';
flush();
$filename = $spotdl->watermark($filename);
echo '<script>location.href = "http://yt.app.darrylmcoder.com/download.php?n='.$filename.'&url=http://spotdl.darrylmcoder.com/'.$filename.'"</script>';
echo '</head> <body></body>';