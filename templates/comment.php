<center>
<div id="comment">
<a name="comment-<?php e($cmnt['num']); ?>"></a>


<b class="pl">&nbsp;</b><b class="pr">&nbsp;</b><div class="title">
<span class="nick"><?php if(trim($cmnt['www'])) { e('<a href="'.$cmnt['www'].'">'); } ?>
<?php e($cmnt['nick']); ?>
<?php if(trim($cmnt['www'])) { e('</a>'); } ?></span>

<a href="<?php e(generate_link($post['id'])); ?>#comment-<?php e($i); ?>">#</a> <?php e($cmnt['date']); ?> <div class="gravatar">
<?php e($cmnt['gravatar']); ?>
</div>
</div>
<div class="comments">


<?php e($cmnt['text']); ?>
<?php if(@$cmnt['attach_size']){ ?>

<div>
<?php e($cmnt['del_attach']); ?> <a href="<?php e($_s['attach_dir'].'/'.$cmnt['attach']); ?>"><?php e($cmnt['attach']); ?></a> (<?php e($cmnt['attach_size']); ?>)</div>
<?}?>
</div>
<div style="clear:both;text-align:center;"><?php if(is_admin()) { ?>

<?php if(trim(@$cmnt['mail'])) e('mail: <b>'.$cmnt['mail'].'</b>, '); ?> <?php e(@$cmnt['ip']); e($cmnt['menu']); ?>

<?php } ?></div>
</div>
</center>
<br/>
