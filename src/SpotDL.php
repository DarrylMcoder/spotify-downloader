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
    $filename = str_replace(" ", "\\ ", $filename);
    $tempfile = '_'. $filename;
    unlink('names.txt');
    file_put_contents('names.txt', 'file \'watermark.mp3\''. PHP_EOL .'file \''. $filename .'\'');
    //rename($filename, $infile);
   // $cmd = 'ffmpeg -f concat -safe 0 -i names.txt -c copy "'. $tempfile .'" 2>&1';

    $cmd = 'ffmpeg -f concat -i names.txt -i \''. $filename .'\' -map_metadata 1 -id3v2_version 3 -write_id3v1 1 -c copy "'. $tempfile .'" 2>&1';
    echo $cmd;
    exec($cmd, $output, $rescode);
    var_dump($output);
    echo $rescode;
    return $tempfile;
  }
}