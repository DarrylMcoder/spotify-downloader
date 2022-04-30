<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $spotifyUrl = escapeshellcmd($spotifyUrl);
    $cmd = 'python /app/spotdl/__main__.py '.$spotifyUrl.' 2>&1';
    exec($cmd,$output,$rescode);
    return $this->parseMusicName($output[1]);
  }

  private function parseMusicName($str){
    preg_match('#"([^"]+)"#',$str,$res);
    var_dump($res);
    $name = $res[1].'.mp3';
    return $name;
  }
  
  public function watermark($filename){
    $filename = escapeshellcmd($filename);
    $infile = preg_replace("#[^\w]#i", "_", $filename) .'.mp3';
    $outfile = '_'. $infile;
    unlink('names.txt');
    file_put_contents('names.txt', 'file \''.  $infile.'\''. PHP_EOL .'file \''. $infile .'\'');
    rename($filename, $infile);
   // $cmd = 'ffmpeg -f concat -safe 0 -i names.txt -c copy "'. $tempfile .'" 2>&1';

    $cmd = 'ffmpeg -f concat -i names.txt -i \''. $infile .'\' -map_metadata 0:1 -id3v2_version 3 -write_id3v1 1 -c:a copy "'. $outfile .'" 2>&1';
    echo $cmd;
    exec($cmd, $output, $rescode);
    rename($outfile, $filename);
    var_dump($output);
    echo $rescode;
    return $filename;
  }
}