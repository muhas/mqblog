<?php
// RSS модуль
Error_Reporting(E_ALL & !~E_NOTICE);
header('Pragma: no-cache');
define('INCLUDED', TRUE);
$_s['include_blog']='../';
include_once '../data/functions.inc';
include_once $_s['pages_dir'].'/m.settings';
$_s['rss_ppp']  = 10;
if(is_numeric($_GET['rss_ppp'])) $_s['rss_ppp']  = $_GET['rss_ppp'];
if(isset($_REQUEST['short'])) $_s['short']=$_REQUEST['short'];
else $_s['short']=0;
function exportjs($t) {
	$t = str_replace("\r","",$t);
	$t = str_replace("\n","",$t);
	$t = str_replace("'","\'",$t);
	return $t;
}
load_plugins();
e("document.write('<div id=\"export\">');\n");
		if(!$pub) {
			if(isset($_GET['tags']) && file_exists($_s['data_dir'].'/tags/cache/'.$_GET['tags']) && file_exists($_s['plugin_dir'].'/tags/funcs.inc')) {
				include_once $_s['plugin_dir'].'/tags/funcs.inc';
				$posts = getbytag($_GET['tags']);
			}else  {
				$posts = get_posts();
			}
        } else {
			$posts[0]=$pub;
			$_s['short']=0;
        }        
        if (empty($posts)) exit();
        @rsort($posts);
        if (sizeof($posts) > $_s['rss_ppp']) $posts = array_slice($posts, $skip, $_s['rss_ppp']);

        for ($i = 0; $i< sizeof($posts); $i++) {

                if($post = post_info($posts[$i])) {
				hook('MQ_RSS_ENTRY_PROCESS_BEFORE');
				$postovoy = "/<div class=\"postovoy\"(.*?)<\/div>/is";
				$post['text'] =  preg_replace($postovoy, "", $post['text']);
                $post['text']=posttext($post['text']);
                if($post['nobr']!="on") $post['text'] = replace_br($post['text']);
                //$text = htmlspecialchars($post['text'], ENT_QUOTES);
                $text = exportjs($post['text']);

                if($_s['short']==1) {
					if(preg_match("#(.*)<cut text=\"(.*)\">#sU", $post['text'], $cut) or preg_match("#(.*)<cut>#sU", $post['text'], $cut)) {
							$origtxt=$post['text'];
							$post['text'] = str_replace("</cut>", "", $post['text']);
							if(isset($cut[2])) {
							$post['text'] = $cut[1].'<a href="'.generate_link($post['id']).'">'.$cut[2].'</a>';
							} else {
							$post['text'] = $cut[1].'<a href="'.generate_link($post['id']).'">'.$_l['more'].'</a>';
									}
					}
					$text = exportjs($post['text']);
                }

                $title = htmlspecialchars($post['title'], ENT_QUOTES);
				$date = date("r", $post['ida']);
				// комментарии
				$comments = $post['c_count'];
				$commentRss = $_s['base_url']."/rss/?id=".$post['id'];
	
	
	e("document.write('<h2><a href=\'".generate_link($post['id'])."\'>".$title."</a></h2>');\n");
	if($_s['short']!=2) e("document.write('".$text."<br/>');\n");		
				}
        }
e("document.write('</div>');\n");

?>
