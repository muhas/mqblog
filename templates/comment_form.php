<center>


<form action="<?php e($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-date" id="commentf">

<input type="hidden" name="action" value="comment" />
<input type="hidden" name="p" value="<?php e($_v['p']); ?>" />
<table cellspacing="2" border="0" cellpadding="0" width="100%">
	<tr>
		<td align="left"><?php e($_l['commentnick']); ?> </td>
		<td align="left"><?php if(is_admin()){ ?>
		<input value="<?php e($_s['author']); ?>" maxlength="28" disabled="disabled"  class="input" />
		<input type="hidden" name="nick" value="<?php e($_s['author']); ?>" />
	<?php } else { ?>
		<input type="text" name="nick" maxlength="28" value="<?php e($nick); ?>" class="input" />
	<?php } ?> </td>
	</tr>
		<td align="left">URL</td>
		<td align="left"><input name="mwww" type="text" value="<?php e($www); ?>"  class="input"/></td>
	</tr>
	</tr>
		<td align="left">e-mail*</td>
		<td align="left"><input name="mmail" type="text" value="<?php e($mail); ?>"  class="input"/></td>
	</tr>
	<?if($_s['capcha'] && !is_admin()) {?>
	</tr>
		<td align="left"><img src="<?php e($_s['base_url']);?>/?s=<?php e(session_id()); ?>" alt="проверочный код" /></td>
		<td align="left"><input type="text" name="capcha" onfocus="if(this.value=='введите код')this.value=''" onblur="if(this.value=='')this.value='введите код'" value="введите код" class="input"/></td>
	</tr>
	<?}?>
</table>
	
<?php e($_s['addcomface']); ?>



<textarea id="text" name="text" rows="8"></textarea>
	<?php if($_s['attach']>0 || is_admin()) { ?>
		<?php e($_l['cmnt_attach']); ?>: <input name="userfile" type="file" /> [<b><?php e(@$cmnt['maxasize']); ?></b>: <?php e($_s['attype']); ?>]
	<?php } ?>
	<?php if($_s['allowhide'] || $post['commpriv']) { ?>
		<input name="privat" type="checkbox" /> <?=$_l['cmnt_privat']?><br />
	<?php } ?>
	<?if((@$post['subs'] || $_s['subscribe']) && !is_admin()) {?>
		<input name="subsmy" type="checkbox" style="width:10px;"  /> подписаться на комментарии
	<?}?>

<?php e($_s['addcomass']); ?>
<br />
<input type="submit" value="<?php e($_l['commentadd']); ?>" style="width:100%;height:25px;font-weight:bold;" /> </form>

</center>

