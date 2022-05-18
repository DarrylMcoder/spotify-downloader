<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $spotifyUrl = escapeshellcmd($spotifyUrl);
    $output;
    $rescode;
    $cmd = 'python /app/spotdl/__main__.py '.$spotifyUrl.' 1>&2';
    exec($cmd,$output,$rescode);
    return $this->parseMusicName($output[1]);
  }

  private function parseMusicName($str){
    preg_match('#"([^"]+)"#',$str,$res);
    $name = $res[1].'.mp3';
    return $name;
  }
  
  public function watermark($filename){
    //not implemented
  }
}