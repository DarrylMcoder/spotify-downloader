<?php 

$q = $_GET['q'];
$type = isset($_GET['type']) ? $_GET['type'] : 'track';

$url = 'https://api.spotify.com/v1/search?q='. $q .'&type='. $type .'&market=ES&limit=10';
$headers = [
  "Accept: application/json",
  "Content-Type: application/json",
  "Authorization: Bearer BQBEg3aowI3HVyA0B8OLT6Z7_ubDHqG0je5pMRkYfWQC0XnR1XwMSkJUblorFfxqHu4S12fSApTtdGAfZNRmgbVy3_-jEdkXs1debVKQLv80Aq1dOIP0Q-tnzuKqsbcXiyVcLZ3UqTHgjT6kb0_0Eea3MjCp-CptaHI"
];
$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
$json = json_decode(curl_exec($c));


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
    echo '<img width="100%" src="'. $item['album']['images'][2] .'">';
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
