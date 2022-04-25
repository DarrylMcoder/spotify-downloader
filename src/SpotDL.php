<?PHP

class SpotDL{
  
  public function download($spotifyUrl){
    $cmd = 'python /app/spotdl '.$spotifyUrl;
    exec($cmd,$output,$rescode);
    foreach($output as $line){
      echo $line;
    }
    echo $rescode;
  }

}