<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $cmd = 'python /app/spotdl/__main__.py '.$spotifyUrl.' 2>&1';
    echo $cmd;
    exec($cmd,$output,$rescode);
    foreach($output as $line){
      //echo $line.'\n';
    }
    return $this->parseMusicName($output[1]);
  }

  private function parseMusicName($str){
    preg_match('#Searching YouTube music for \"(.*?)\"#i',$str,$res);
    $name = $res[1][0].'.mp3';
    return $name;
  }
}