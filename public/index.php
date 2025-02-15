<?php 

ini_set('error_reporting', E_ALL ^ E_NOTICE); 
ini_set('display_errors', 1);
include('../src/functions.php');

$q = isset($_GET['q']) ? $_GET['q'] : null;
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

if(isset($q)){
  $url = make_spotify_url($q,$offset);
  $json = get_spotify_json($url);
}

function make_page_url($q, $offset = 0){
  $url = 'http://spotdl.darrylmcoder.com?q='.urlencode($q).'&offset='.$offset;
  return $url;
}

function make_spotify_url($q, $offset = 0){
  $url = 'https://api.spotify.com/v1/search?q='.urlencode($q).'&type=track&market=ES&limit=10&offset='.$offset;
  return $url;
}

function get_spotify_json($url){
  $access_token = get_access_token();
  $headers = [
    "Accept: application/json",
    "Authorization: Bearer $access_token"
  ];
  $c = curl_init($url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
  $res = curl_exec($c);
  $json = json_decode($res, true);
  return $json;
}

function get_access_token(){
  $url = 'https://accounts.spotify.com/api/token';
  $postdata = 'grant_type=client_credentials';
  $client_key    = getenv('SPOTIFY_KEY');
  $client_secret = getenv('SPOTIFY_SECRET');
  $basic_auth_string   = base64_encode($client_key .':'. $client_secret);
  $headers = [
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Basic $basic_auth_string"
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
    <title>Mp3 Music Downloader</title>
    <meta name="viewport" content="width=320, initial-scale=1">
    <meta charset="utf-8">
    <meta name="description" content="DarrylMcoder's Music Downloader, Search and download any song as mp3">
    <style>
      body, html {
        min-width: 100%;
        min-height: 100%;
        margin: 0;
        padding: 0;
        font: Arial 14px;
      }
      .img{
        margin-top:20px;
        border: solid black 1px;
      }
    </style>
    <link rel="stylesheet" href="http://dstatic.darrylmcoder.com/assets/style.css">
    <script src="http://dstatic.darrylmcoder.com/assets/script.js"> </script>
    <script>
      
    </script>
  </head>
  <body>
    <?php echo file_get_contents('http://dstatic.darrylmcoder.com/assets/header.html') ?>
    <div class="content">
      <div class="pagetitle">
        Music Downloader
      </div><br>
      
      <form action="" method="get">
        <label for="q">
          <h3>
            Search for any song on Spotify.
          </h3>
        </label>
        <input type="text" class="input" placeholder="Search for a song..." name="q" value="<?=$q?>">
        <button type="submit" class="go">
          Search
        </button>
      </form>
      <br><br>
<?php

if(isset($q)){
foreach($json['tracks']['items'] as $item){
  $artists = '';
  foreach($item['artists'] as $artist){
    $artists .= $artist['name'] . ', ';
  }
  $artists = trim($artists, ', ');
  $name = $artists .' - '. $item['name'];
  $img_url = $item['album']['images'][1]['url'];
  $preview_url = isset($item['preview_url']) ? $item['preview_url'] : '';
  $url = $item['external_urls']['spotify'];
  echo '<div class="opts">';
    echo '<img class="img" width="250" height="250" src="'. $img_url .'">';
    echo '<p>'. $name .'</p>';
    if($preview_url != ''){
      echo '<audio controls>';
        echo '<source src="'. $preview_url .'" type="audio/mpeg">';
      echo '</audio>';
    }
    echo "<a href=\"download.php?name=". urlencode($name)."&url=". urlencode($url)."&img_url=". urlencode($img_url)."&preview_url=". urlencode($preview_url)."\">";
      echo '<button class="go">';
      echo 'Download';
      echo '</button>';
    echo '</a>';
  echo '</div>';
}
  
  $prev = make_page_url($q,$offset - 10);
  $next = make_page_url($q,$offset + 10);
  if(($offset - 10) >= 0){
    echo '<a href="'. $prev .'">&lt;&lt;Previous  </a>';
  }
  echo " | ";
  if(($offset + 10) <= $json['tracks']['total']){
    echo '<a href="'. $next .'">  Next&gt;&gt;</a>';
  }
}elseif(!isset($q)){
  include('./config.php');
  //get total songs downloaded
  $sql = 'SELECT SUM(downloads) FROM songs';
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = $result->fetch_all(MYSQLI_ASSOC);
  error_log(json_encode($rows));
  echo '<b>Total of '. $rows[0]['SUM(downloads)'] .' songs downloaded.</b>';
  
  //get 10 recent downloads
  $sql = 'SELECT * FROM songs ORDER BY timestamp DESC LIMIT 10';
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $rows = $result->fetch_all(MYSQLI_ASSOC);
  foreach($rows as $row){
    $name        = $row['name'];
    $img_url     = $row['img_url'];
    $preview_url = $row['preview_url'];
    $url         = $row['url'];
    $downloads   = $row['downloads'];
    $id          = $row['id'];
    $timestamp   = $row['timestamp'];
    echo '<div class="opts">';
    echo '<img class="img" width="250" height="250" src="'. $img_url .'">';
    echo '<p>'. $name .'</p>';
    echo '<p>';
      echo $downloads . ' ' . (($downloads == 1) ? 'download' : 'downloads');
      echo '<br>';
      // set the default timezone to use.
      date_default_timezone_set('EST');
      echo 'Last downloaded on '. date('F j, Y, g:i a', $timestamp);
    echo '</p>';
    if(isset($preview_url) && $preview_url != ''){
      echo '<audio controls>';
        echo '<source src="'. $preview_url .'" type="audio/mpeg">';
      echo '</audio>';
    }
    echo "<a href=\"download.php?name=". urlencode($name)."&url=". urlencode($url)."&img_url=". urlencode($img_url)."&preview_url=". urlencode($preview_url)."\">";
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
