<?php
// RSS модуль
Error_Reporting(E_ALL & !~E_NOTICE);
header('Pragma: no-cache');
header('Content-type: application/xml');
define('INCLUDED', TRUE);
$_s['include_blog']='../';
include_once '../data/functions.inc';
include_once $_s['pages_dir'].'/m.settings';
include_once $_s['lang_dir'].'/'.$_s['lang'].'.inc';
$_s['comment_dateformat']='r';

load_plugins();

$_s['rss_title']       = $_s['blogname'];
$_s['rss_link']        = $_s['base_url'];
if (isset($_GET['id']) && file_exists($_s['comment_dir'].'/'.$_GET['id'])) { $_s['rss_link'] = generate_link($_GET['id']); }
$_s['rss_description'] = $_s['blogdescription'];
$_s['rss_language']    = 'ru';

$_s['rss_logo_file']   = $_s['base_url'].'/img/logosmall.png';
$_s['rss_logo_title']  = $_s['blogname'];
$_s['rss_logo_link']   = $_s['base_url'];
$_s['rss_ppp']         = 10;

//if ($p) $_s['rss_ppp'] = $p;

if(isset($_REQUEST['short'])) $_s['short']=$_REQUEST['short'];
else $_s['short']=0;
if($_s['nocomments']==1) $_s['rsscom']=0; 

hook('MQ_RSS_BEFORE_ALL');

e('<?xml version="1.0" encoding="'.$_s['encoding'].'"?>');
?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">
<channel>
<title><?php e($_s['rss_title']); ?></title>
<link><?php e($_s['rss_link']); ?></link>
<description></description>
<lastBuildDate><?php e(date("r", time())); ?></lastBuildDate>
<sy:updatePeriod>hourly</sy:updatePeriod>
<sy:updateFrequency>1</sy:updateFrequency>
<? if($_s['rsscom']==1) { ?>
<wfw:commentRss><?php e($_s['base_url']."/rss/?last_comments"); ?></wfw:commentRss>
<? } ?>
<language><?php e($_s['rss_language']); ?></language>
<image>
<url><?php e($_s['rss_logo_file']); ?></url>
<title><?php e($_s['rss_logo_title']); ?></title>
<link><?php e($_s['rss_logo_link']); ?></link>
</image>
<?php
if (isset($_GET['id']) && file_exists($_s['comment_dir'].'/'.$_GET['id'])) {

		hook('MQ_RSS_BEFORE_COMMENTS_PROCESS');
		$_v['p'] = $_GET['id']; // для топиков и скрытых
        if($post = post_info($_GET['id'])) {
  		$comment = file($_s['comment_dir'].'/'.$_GET['id']);
        for ($i=0; $i < $post['c_count']; $i++) {
                $c = explode('¦¦', $comment[$i]);
                unset($cmnt);
                $cmnt['num']=$i;
				cmt_prepare();

		if(@$cmnt['priv']!=1) {
        $nick = $date = $text = $title = '';
  		$nick = htmlspecialchars($cmnt['nick'], ENT_QUOTES);
  		$date = $cmnt['date'];  		
  		$title = $post['title'];
  		$text = htmlspecialchars($cmnt['text'], ENT_QUOTES);
?>
<item>
<? hook('MQ_RSS_COMMENT_SHOW_BEFORE'); ?>
<title><![CDATA[<?php e($nick." : ".$title); ?>]]></title>
<link><?php e(generate_link($_GET['id'])."#comment-".$cmnt[num]);?></link>
<description><?php e($text); ?></description>
<author><![CDATA[<?php e($nick); ?>]]></author>
<pubDate><?php e($date); ?></pubDate>
<guid  isPermaLink='true'><?php e(generate_link($post['id'])."#comment-".$cmnt[num]); ?></guid>
<? hook('MQ_RSS_COMMENT_SHOW_AFTER'); ?>
</item>
<?php
	     }
      unset($c);
    }
  }//!post
}
else if(isset($_GET['last_comments']) && file_exists($_s['data_dir'].'/lcomments')) {
        $time_offset = $_s['time_offset'] * 3600;
        $lc = file($_s['data_dir'].'/lcomments');
        $sz=sizeof($lc);
        if($hs!='') {
        if($hs>sizeof($lc)) $sz=sizeof($lc);
        else $sz=$hs;
        }
		for ($i=0; $i<$sz; $i++) {
	        $cline = explode('¦¦', $lc[$i]);
	        $comment = file($_s['comment_dir'].'/'.$cline[0]);
	        $c = ''; $cmnt['num']=$cline[1]=str_replace("\n",'',$cline[1]);
	        $post['id'] = $cline[0];
	        $post=post_info($post['id']);
	        $c = explode('¦¦', $comment[$cline[1]]);
            cmt_prepare();
            $nick = $date = $text = $title = '';
            $nick = htmlspecialchars($cmnt['nick'], ENT_QUOTES);
			$date = $cmnt['date'];
			$title = $post['title'];
			$text = htmlspecialchars($cmnt['text'], ENT_QUOTES);
	        if($cmnt['priv']!=1) {
?>
<item>
<? hook('MQ_RSS_COMMENT_SHOW_BEFORE'); ?>
<title><![CDATA[<?php e($nick." : ".$title); ?>]]></title>
<link><?php e(generate_link($post['id'])."#comment-".$cmnt[num]); ?></link>
<description><?php e($text); ?></description>
<author><![CDATA[<?php e($nick); ?>]]></author>
<dc:creator><![CDATA[<?php e($nick); ?>]]></dc:creator>
<pubDate><?php e($date); ?></pubDate>
<guid  isPermaLink='true'><?php e(generate_link($post['id'])."#comment-".$cmnt[num]); ?></guid>
<? hook('MQ_RSS_COMMENT_SHOW_AFTER'); ?>
</item>
<?php
		}
  }
}
elseif (isset($_GET['info'])) {
	
}
else {
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

                $post_id = $post['id'];

                hook('MQ_RSS_ENTRY_PROCESS_BEFORE');
				$postovoy = "/<div class=\"postovoy\"(.*?)<\/div>/is";
				$post['text'] =  preg_replace($postovoy, "", $post['text']);
                $post['text']=posttext($post['text']);
                if($post['nobr']!="on") $post['text'] = replace_br($post['text']);
                $text = htmlspecialchars($post['text'], ENT_QUOTES);

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
                $text = htmlspecialchars($post['text'], ENT_QUOTES);
                }

                if ($_s['short']==2) $text = "";
                $title = htmlspecialchars($post['title'], ENT_QUOTES);
				$date = date("r", $post['ida']);
				// комментарии
				$comments = $post['c_count'];
				$commentRss = $_s['base_url']."/rss/?id=".$post['id'];
?>
<item>
<? hook('MQ_RSS_ENTRY_SHOW_BEFORE'); ?>
<title><![CDATA[<?php e($title); ?>]]></title>
<link><?php e(generate_link($post_id)); ?></link>
<description><?php e($text); ?></description>
<pubDate><?php e($date); ?></pubDate>
<guid  isPermaLink='true'><?php e(generate_link($post_id)); ?></guid>
<? if($_s['rsscom']==1) { ?>
<wfw:commentRss><?php e($commentRss); ?></wfw:commentRss>
<slash:comments><?php e($comments); ?></slash:comments>
<? } hook('MQ_RSS_ENTRY_SHOW_AFTER'); ?>
</item>
<?php
		
				}
        }

}
?>
</channel>
</rss>
