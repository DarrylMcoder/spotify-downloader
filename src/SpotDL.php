<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $spotifyUrl = escapeshellcmd($spotifyUrl);
    $cmd = 'python /app/spotdl/__main__.py '.$spotifyUrl.' -f - 1>&2';
    $fp = popen($cmd,"r");
    while(!feof($fp)){
      echo fgets($fp, 4096);
    }
    pclose($fp);
    
    //return $this->parseMusicName($output[1]);
  }

  private function parseMusicName($str){
    preg_match('#"([^"]+)"#',$str,$res);
    var_dump($res);
    $name = $res[1].'.mp3';
    return $name;
  }
  
  public function watermark($filename){
    //not implemented
  }
}