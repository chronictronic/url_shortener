<?php
include 'Shortener.php';
if(isset($_POST['url'])){ 
    $shortener = new Shortener();
    $link = $shortener->getShortUrl($_POST['url']);
    echo 'http://u3086.indigo.elastictech.org/?t='.$link;
}