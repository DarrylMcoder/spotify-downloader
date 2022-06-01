<?PHP

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
$name = $_GET['name'];
$url = $_GET['url'];
$img_url = $_GET['img_url'];
$preview_url = $_GET['preview_url'];
$downloads = 1;
$timestamp = time();
require('../src/SpotDL.php');
$spotdl = new SpotDL();
$filename = $spotdl->download($url);
echo "<head><script>location.href='http://yt.app.darrylmcoder.com/download.php?n=". $filename ."&url=http://spotdl.darrylmcoder.com/". $filename ."'</script></head><body></body>";