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
    $infile = 'temp_'. $filename;
    unlink('names.txt');
    file_put_contents('names.txt', 'file "watermark.mp3"'. PHP_EOL .'file "'. $infile .'"');
    rename($filename, $infile);
    $cmd = 'ffmpeg -f concat -i names.txt -c copy "'. $filename .'" 2>&1';
    echo $cmd;
    exec($cmd, $output, $rescode);
    var_dump($output);
    echo $rescode;
    return $filename;
  }
}