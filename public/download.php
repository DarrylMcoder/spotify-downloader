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

function listFolderFiles($dir){
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    echo '<ol>';
    foreach($ffs as $ff){
        echo '<li>'.$ff;
        if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
        echo '</li>';
    }
    echo '</ol>';
}

listFolderFiles('/app/public/');