<?php
// Mosquito non-mysql php blog-engine with plugins
// © Zorg <ekumena@gmail.com>, 2007-2008
// © muhas <muhas@muhas.ru>, 2009-2011
// лицензия GNU GPL (http://www.gnu.org/licenses/gpl.html)
error_reporting(E_ALL & !~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
define('INCLUDED', TRUE);
if(!isset($_include)) $_s['include_blog']='';
else $_s['include_blog']=$_include.'/';

include_once 'data/functions.inc';
include_once $_s['pages_dir'].'/m.settings';
include_once $_s['lang_dir'].'/'.$_s['lang'].'.inc';
if(file_exists($_s['pages_dir'].'/m.lock')){header('Location: install.php');}

load_plugins();
$_m = $_v = $_c = array();

$_s['addcomface']=$_s['dwnpostface_']=$_s['addcomass']=$_s['uppostface']=$_s['dwnpostface']=$_s['stat']=$_s['pages_link']=$_l['cmnt_for_cmt']='';

$time_offset = $_s['time_offset'];
foreach ($_GET as $k => $v)  $_v[$k] = $v;
foreach ($_POST as $k => $v) $_v[$k] = $v;
foreach ($_COOKIE as $k => $v) $_c[$k] = $v;
if(is_admin()) {include_once $_s['lang_dir'].'/'.$_s['lang'].'_config.inc';}
$_s['title'] = $_s['blogname'];

if(isset($_v['st'])) $_s['blogsort'] = 1;
if(isset($_v['tl'])) $_s['list_tags'] = 1;
//if(is_numeric($_v['ppp']) && $_v['ppp'] < 100) $_s['ppp'] = $_v['ppp'];

// seo <title>
if(isset($_v['p'])) {
	if($post=post_info($_v['p'])) { if(trim($post['title'])) $_s['title']=$post['title'].' - '.$_s['blogname']; }
}
if(isset($_v['pg'])) $_s['title']=translit($_v['pg'],1).' - '.$_s['blogname'];

// интегрированная, простая капча
if(@$_s['capcha'] && !isset($_v['s'])) {
    session_name("s");
    session_start();
}

if(isset($_v['s']) && $_s['capcha']) {
    session_name("s");
    session_start();
	$text = '';
	$_SESSION['capcha'] = '';
	for($i = 0; $i < 4; $i++) {
		$code = mt_rand(0,9);
		$_SESSION['capcha'] .= $code;
		$text .= $code.' ';
	}
$image = imagecreate(65,18);
$bgcolor = ImageColorAllocate($image, 247, 247, 247);
$black = ImageColorAllocate($image,0,0,0);
imagefill($image,0,0,$bgcolor);
//делаем немного шума шум
for($j=0; $j<65; $j++){
	for($i=0; $i<5; $i++){
        $color = imagecolorallocate ($image, rand(0,255), rand(0,255), rand(0,255));
		imagesetpixel($image,rand(0,65),rand(0,18),$color);
	}
	// для параноиков 
	// imageline($image, rand(0, 18), rand(1, 65), rand(0, 65), rand(1, 18), $color); 
}
//заканчиваем с шумом
imagestring($image,4,5,2,$text,$black);
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
exit();
}

hook('MQ_HEADER_BEFORE');
// Шаблоны
if (file_exists($_s['tpl_dir'].'/head.php')) $dotpl=".php";
else $dotpl=".html";
$tpl['header']='head'.$dotpl;
$tpl['day']='day'.$dotpl;
$tpl['post']='post'.$dotpl;
$tpl['post_topic']='post_topic'.$dotpl;
$tpl['comment']='comment'.$dotpl;
$tpl['comment_a']='comment_a'.$dotpl;
$tpl['comment_last']='comment_last'.$dotpl;
$tpl['comment_form']='comment_form'.$dotpl;
$tpl['foother']='footer'.$dotpl;
$tpl['post_list']='post_list'.$dotpl;
$tpl['post_form']='post_form'.$dotpl;
$tpl['login_form']='login_form'.$dotpl;

if (get_magic_quotes_gpc()) foreach($_v as $k => $v) $_v[$k] = stripslashes($v);

$_v['action'] = (isset($_v['action']) ? $_v['action'] : '');

// кексы: ник, url, мейл, uid
$nick=cecx('mqnick',(isset($_v['nick'])?$_v['nick']:''));
$www=cecx('mqurl',(isset($_v['mwww'])?$_v['mwww']:''));
$mail=cecx('mqmail',(isset($_v['mmail'])?$_v['mmail']:''));
if(isset($_c['mquid'])) $_s['user_uid']=$_c['mquid'];
else setcookie('mquid', md5(time()), time()+6592000);

if ($_v['action'] == 'login' || (@$_v['scword']==$_s['pass'])) {
		if(@$_v['scword']==$_s['pass']) $_v['pass']=$_v['scword'];
        if (isset($_v['pass']) and $_v['pass'] == $_s['pass']) {
                hook('MQ_LOGIN_BEFORE');
                setcookie('sid', md5($_s['pass']), time() + 604800, '/');
                hook('MQ_LOGIN_AFTER');
                header("Location: ".$_SERVER['PHP_SELF']);
        } else {
                hook('MQ_LOGIN_FORM_SHOW_BEFORE');
                include_once $_s['tpl_dir'].'/'.$tpl['login_form'];
        }
        exit();
} else if ($_v['action'] == 'logout') {
        if (is_admin()) {
                hook('MQ_LOGOUT_BEFORE');
                setcookie('sid', '', time()-3800, '/');
        }
        header('Location: '.$_SERVER['PHP_SELF']);
}

include_once $_s['tpl_dir'].'/'.$tpl['header'];

if(isset($_v['scword']) && file_exists($_s['act_dir'].'/'.$_v['scword'].'.inc')) $_v['action']=$_v['scword'];

if(trim($_v['action']) && file_exists($_s['act_dir'].'/'.$_v['action'].'.inc')) {
	hook('MQ_ACTION_'.strtoupper($_v['action']).'_BEFORE');
	include $_s['act_dir'].'/'.$_v['action'].'.inc';
	hook('MQ_ACTION_'.strtoupper($_v['action']).'_AFTER');
} else {

if (isset($_v['pg'])) page($_v['pg']);
else if (isset($_v['p'])) {
     $post = post_info($_v['p']);

   	 if(!$post) page($_s['page404']);
   	 else {
        $post = post_info($_v['p']);
       
		$post['text'] = posttext($post['text']);
		if($post['nobr']!="on") $post['text'] = replace_br($post['text']);
		
        $post['edit'] = $post['del'] = $d = '';
        $post['title'] = (@trim($post['title']) ? $post['title'] : $_l['notitle']);
		
        if($_s['confirm_post']) $d = 'onclick="return confirm(\''.$_l['confirm'].' ['.$post['title'].']\')"';

        post_menu();

        hook('MQ_POST_SHOW_BEFORE');
        include_once $_s['tpl_dir'].'/'.$tpl['post'];

		// Comments
        e('<a name="comment"></a>');

        $comment = file($_s['comment_dir'].'/'.$_v['p']);
        for ($i=0; $i < $post['c_count']; $i++) {
                $c = explode('¦¦', $comment[$i]);
                unset($cmnt);
                $cmnt['num']=$i;
				cmt_prepare();

		if(@$cmnt['priv']==1 && !is_admin()) { } else {
				$cmnt['himnu']=$cmnt['del_attach']=$cmnt['del']='';
				comment_menu();

                hook('MQ_COMMENT_SHOW_BEFORE');
        		if($cmnt['aauch']==1) include $_s['tpl_dir'].'/'.$tpl['comment_a'];
        		else include $_s['tpl_dir'].'/'.$tpl['comment'];
	     }
                unset($c);
        }

		if(!@$post['nocomm'] && !$_s['nocomments']) {
				if(trim($post['attach'])) $_s['attach']=$post['attach'];
                if($_s['attach']>1024) @$cmnt['maxasize']=round(($_s['attach']/1024),3).' Mb';
				else @$cmnt['maxasize']=$_s['attach'].' Kb';
		hook('MQ_COMMENT_FORM_SHOW_BEFORE');
        include_once $_s['tpl_dir'].'/'.$tpl['comment_form'];
        restoreDraft();
        }
 }
} else {
	blog();
}

}

hook('MQ_FOOTHER_BEFORE');
e('<!-- mqbm: '.VERSION.' -->');
include_once $_s['tpl_dir'].'/'.$tpl['foother'];
hook('MQ_FOOTHER_AFTER');
?>
