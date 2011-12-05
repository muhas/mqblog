<?php
$plugin_name = 'PSearch';
$plugin_version = '0.6';
$plugin_description = $_l['psrch_desc'];
$plugin_author = 'zorg & muhas';

register_hook('MQ_POSTS_GET_BEFORE', 'search');
?>
