<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $cmd = 'python /app/spotdl/__main__.py '.$spotifyUrl.' 2>&1';
    echo $cmd;
    exec($cmd,$output,$rescode);
    var_dump($output);
    echo $rescode;
  }

}