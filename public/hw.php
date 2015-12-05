<h3>Mary Jatty Embedded</h3>

<?php
	echo 'hello world';
    $baseUrl = 'http://edchant.tk:8080/';
    $src = $baseUrl;
?>

<audio autoplay controls>
  <source src="<?php echo($src); ?>" type='audio/wav' autoplay>
  Your browser does not support the audio tag.
</audio> <?php
