<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mary Audio Tag</title>
</head>

<body>
  <h1>MaryTTS Audio embeded</h1>

  <?php
  $input = "we are only boys and girls";
  $locale = "en_US"; // can't be null since paramOrDefault valuse in java is empty 
  $voice = null;

  embedMaryTTS($input, $locale, $voice);
  
  function embedMaryTTS($input, $locale, $voice) {
    $baseUrl = 'http://edchant.tk:8080/';
    //$baseUrl = 'http://edchant.tk:1234/example13/';
    //$baseUrl = 'http://127.0.0.1:8080/';
    //$baseUrl = 'http://127.0.0.1:1234/example13/';
    //$baseUrl = 'http://192.168.10.10:1234/example13/';
    //$baseUrl = 'http://192.168.10.10:8080/';   
    
    $key = getenv('MARYTTS_HMAC_SECRET');
    $keyBytes = base64_decode($key);

    //$stringToSign = $input . $locale . $expires;
    //$stringToSign = $input . $locale;
    $stringToSign = $input;
    $signatureBytes = hash_hmac("sha256", $stringToSign, $keyBytes, true);
    $signature = base64_encode($signatureBytes);

    $src = $baseUrl . '?';
    $src .= "text=" . urlencode($input);
    $src .= "&locale=" . urlencode($locale);  
    $src .= "&voice=" . urlencode($voice);      
    $src .= "&signature=" . urlencode($signature);

    echo 'key: ', $key, '<br>';
    echo 'keyBytes: ', $keyBytes, '<br>';
    echo 'signatureBytes: ', $signatureBytes, '<br>';
    echo 'signature: ', $signature, '<br>';
    echo $src, '<br><br>'; ?>

    <audio autoplay controls>
      <source src="<?php echo($src); ?>" type='audio/wav' autoplay>
      Your browser does not support the audio tag.
    </audio> <?php
  } ?>   
</body>
</html>
