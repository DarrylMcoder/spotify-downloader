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
    $infile = 'temp_'. urlencode($filename);
    rename($filename, $infile);
    $cmd = 'ffmpeg -i "concat:watermark.mp3|'. $infile .'" -i '. $infile .' -acodec copy "'. $filename .'" -map_metadata 0:1 2>&1';
    echo $cmd;
    exec($cmd, $output, $rescode);
    var_dump($output);
    echo $rescode;
    return $filename;
  }
}