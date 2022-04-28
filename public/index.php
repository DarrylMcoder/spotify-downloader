<?php 

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
$q = isset($_GET['q']) ? urlencode($_GET['q']) : null;
$type = 'track';

$url = 'https://api.spotify.com/v1/search?q='. $q .'&type='. $type .'&market=ES&limit=10';
$access_token = get_access_token();
$headers = [
  "Accept: application/json",
  "Authorization: Bearer $access_token"
];
var_dump($headers);
var_dump($url);
$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($c);
$json = json_decode($res, true);

function get_access_token(){
  $url = 'https://accounts.spotify.com/api/token';
  $postdata = 'grant_type=client_credentials';
  $headers = [
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Basic ZmYwM2I5YjM5MWEwNGVlYWI4YzFmYWI3YWQ1NTUwZTM6MjE5OTMxZTY5MjM5NGI0Yjk4ZjVlZDBhNDY3ODViZmE="
  ];
  $c = curl_init($url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($c, CURLOPT_POST, true);
  curl_setopt($c, CURLOPT_POSTFIELDS, $postdata);
  $res = curl_exec($c);
  $json = json_decode($res, true);
  $access_token = $json['access_token'];
  return $access_token;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta charset="utf-8">
    <style>
      body, html {
        min-width: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
        font: Arial 14px;
      }
    </style>
    <link rel="stylesheet" href="http://dstatic.darrylmcoder.com/assets/style.css">
    <script src="http://dstatic.darrylmcoder.com/assets/script.js"> </script>
    <script type="text/javascript">
    loadLinks('http://dstatic.darrylmcoder.com/assets/links.json','myOverlay');
    </script>
  </head>
  <body>
    <?php echo file_get_contents('http://dstatic.darrylmcoder.com/assets/header.html') ?>
    <div class="content">
      <div class="pagetitle">
        Music Downloader
      </div><br>
      <form action="" method="get">
        <input type="text" class="input" name="q">
        <button type="submit" class="go">
          Search
        </button>
      </form>
      <h3>
        <?php echo isset($q) ? 'Search results for "'. $q .'"' : ''; ?>
        <hr><hr>
      </h3>
<?php

if(isset($q)){
foreach($json['tracks']['items'] as $item){
  echo '<div class="opts">';
    echo '<img width="'. $item['album']['images'][2]['width'] .'" height="'. $item['album']['images'][2]['height'] .'" src="'. $item['album']['images'][2]['url'] .'">';
    echo '<p>'. $item['name'] .'</p>';
    if(isset($item['preview_url'])){
      echo '<audio controls>';
        echo '<source src="'. $item['preview_url'] .'" type="audio/mpeg">';
      echo '</audio>';
    }
    echo '<a href="download.php?url='. $item['external_urls']['spotify'] .'">';
      echo '<button class="go">';
      echo 'Download';
      echo '</button>';
    echo '</a>';
  echo '</div>';
}
}

?>
    </div>
     <?php echo file_get_contents('http://dstatic.darrylmcoder.com/assets/footer.html') ?>
  </body>
</html>
