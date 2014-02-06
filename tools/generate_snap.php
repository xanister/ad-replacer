<?php

$start_time = time();

// Gather data
$url = isset($_GET['url']) ? $_GET['url'] : 'http://www.glamour.com';
$ad_target = isset($_GET['ad_target']) ? $_GET['ad_target'] : 'collage300x250_frame';
$render_delay = isset($_GET['render_delay']) ? $_GET['render_delay'] : 0;

// Save the new image
$info = pathinfo($_FILES['image']['name']);
$newname = "ad_image_".time()."." . $info['extension'];
$target = __DIR__ . '/images/' . $newname;
move_uploaded_file($_FILES['image']['tmp_name'], $target);
$replacement_image = "http://localhost/ad_replacer/tools/images/" . $newname;

// Execute
$command = "/usr/local/bin/phantomjs /var/www/html/ad_replacer/js/ad_replace.js $url $ad_target '$replacement_image' $render_delay";
$last_line = system($command, $result);

$run_time = time() - $start_time;

echo $run_time;

?>
