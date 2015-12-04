<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mary Audio Tag</title>
</head>

<body>
  <h1>MaryTTS Audio embeded</h1>

  <?php
  $input = "this is my nice text";
  $locale = "en_US";

  embedMaryTTS($input, $locale);
  
  function embedMaryTTS($input, $locale) {
    $baseUrl = 'http://127.0.0.1:1234/example13/';
    //$baseUrl = 'http://192.168.10.10:1234/example13/';

    //$key = getenv('HMAC_SECRET');
    //$key = getenv('MARYTTS_HMAC_SECRET');
    $key = 'MARYTTS_HMAC_SECRET';
    $keyBytes = base64_decode($key);

    //$stringToSign = $input . $locale . $expires;
    $stringToSign = $input . $locale;
    $signatureBytes = hash_hmac("sha256", $stringToSign, $keyBytes, true);
    $signature = base64_encode($signatureBytes);

    $src = $baseUrl . '?';
    $src .= "text=" . urlencode($input);
    $src .= "&locale=" . urlencode($locale);  
    $src .= "&signature=" . urlencode($signature);

    echo 'signatureBytes: ', $signatureBytes, '<br>';
    echo 'signature: ', $signature, '<br>';
    echo $src, '<br><br>'; ?>

    <audio autoplay controls>
      <!--<source src='http://192.168.10.10:59125/process?INPUT_TYPE=TEXT&AUDIO=WAVE_FILE&OUTPUT_TYPE=AUDIO&LOCALE=en_US&INPUT_TEXT=%22Hello%20world%22' type='audio/wav'>-->
      <!--<source src='http://192.168.10.10:8080/?text=Hallo&locale=de' type='audio/wav'>-->
      <!--<source src='https://powerful-crag-5441.herokuapp.com/?text=Hallo&locale=de' type='audio/wav' autoplay>-->
      <!--<source src='http://127.0.0.1:8080/?text=Hallo&locale=de' type='audio/wav' autoplay>-->
      <!--<source src='http://127.0.0.1:1234/example12' type='audio/wav' autoplay>-->
      <!--<source src='http://192.168.10.10:1234/example12' type='audio/wav' autoplay>-->

      <source src="<?php echo($src); ?>" type='audio/wav' autoplay>
      Your browser does not support the audio tag.
    </audio> <?php
  } ?>   
</body>
</html>