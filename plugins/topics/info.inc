<?php
$plugin_name = 'Topics';
$plugin_version = '0.5.1';
$plugin_description = $_l['topics_desc'];
$plugin_author = 'zorg & muhas';

register_hook('MQ_POSTS_GET_BEFORE', 'topics');
register_hook('MQ_POST_MENU', 'tpcmenu');
register_hook('MQ_POST_INFO', 'tpcstat');

register_hook('MQ_POST_FORM_SHOW_BEFORE', 'tpcform');
register_hook('MQ_POST_SETS', 'tpcsave');

$actions = array(
        'tpchelp'        => $_l['topics_hhelp'],
        'tpcsett'        => $_l['topics_sett']
);
?>
