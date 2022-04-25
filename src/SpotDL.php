<?PHP
    
namespace SpotDL;

class SpotDL{

  private cmd;
  
  public function download($spotifyUrl){
    $cmd = 'python /app/spotdl '.$spotifyUrl;
    exec($cmd,$output,$rescode);
    
  }

}