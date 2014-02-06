<?php

$start_time = time();

$url = isset($_GET['url']) ? $_GET['url'] : 'http://www.glamour.com';
$render_delay = isset($_GET['render_delay']) ? $_GET['render_delay'] : 0;

$command = "/usr/local/bin/phantomjs /var/www/html/ad_replacer/js/ad_replace_all.js $url $render_delay";
$last_line = system($command, $result);

$run_time = time() - $start_time;

echo $run_time;

?>
