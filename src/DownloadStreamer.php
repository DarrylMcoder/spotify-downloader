<?PHP
    
namespace YouTube;
class DownloadStreamer extends YouTubeStreamer{
  
  protected $filename;
  protected $partLength = 50000;
  protected $iteration = 0;
  protected $length = 10000000;
  
  public function setDownloadedFileName($name){
    $this->filename = $name;
  }
  
  public function download($url){
    $this->stream($url);
  }
  
  public function headerCallback($c,$data){
    if($this->iteration === 0){
      $preg = "#Content-Range:\sbytes\s[0-9]*-[0-9]*\/([0-9]*)#i";
      preg_match($preg,$data,$m);
      $this->length = $m[1];
      
        // this should be first line
        if (preg_match('/HTTP\/[\d.]+\s*(\d+)/', $data, $matches)) {
            $status_code = $matches[1];

            // if Forbidden or Not Found -> those are "valid" statuses too
            if ($status_code == 200 || $status_code == 206 || $status_code == 403 || $status_code == 404) {
                $this->headers_sent = true;
                $this->sendHeader(rtrim($data));
            }

        } else {

            // only headers we wish to forward back to the client
            $forward = array('content-type','accept-ranges');

            $parts = explode(':', $data, 2);

            if ($this->headers_sent && count($parts) == 2 && in_array(trim(strtolower($parts[0])), $forward)) {
                $this->sendHeader(rtrim($data));
            }
          $this->sendHeader("Content-length: ".$this->length);
        }
      if(!$this->name_set){
        $this->sendHeader("Content-Disposition: attachment; filename=\"".$this->filename."\"");
        $this->name_set = true;
      }
      
    }
    return strlen($data);
  }
    public function stream($url)
    {
        $this->url = $url;
        $ch = curl_init();

        // otherwise you get weird "OpenSSL SSL_read: No error"
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_BUFFERSIZE, $this->buffer_size);
        curl_setopt($ch, CURLOPT_URL, $url);

        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // we deal with this ourselves
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'headerCallback']);

        // if response is empty - this never gets called
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, [$this, 'bodyCallback']);

        $headers = array();
        if (isset($_SERVER['HTTP_RANGE'])) {
          $headers[] = 'Range: ' . $_SERVER['HTTP_RANGE'];
          $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0';
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_exec($ch);
        }else{
          $start = 0;
          $end = $start + $this->partLength - 1;
          while(true){
            $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0';
            $headers[] = "Range: bytes=$start-$end";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_exec($ch);
            $start += $this->partLength;
            $end   += $this->partLength;
            $end = ($end < $this->length) ? $end : "";
            $this->iteration++;
            if($start > $this->length){
              break;
            }
          }
        }

        // TODO: $this->logError($ch);
        $error = ($ret === false) ? sprintf('curl error: %s, num: %s', curl_error($ch), curl_errno($ch)) : null;
        curl_close($ch);
     

        // if we are still here by now, then all must be okay
        return true;
    }
  
  protected function getLength($url){
    $c = curl_init($url);
    curl_setopt($c,CURLOPT_NOBODY,true);
    curl_setopt($c,CURLOPT_HEADER,true);
    curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($c);
    $preg = "#Content-length:(.*?)\s#i";
    preg_match($preg,$result,$m);
    return $m[1];
  }
}
    
?>
