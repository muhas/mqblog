
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">//<![CDATA[
  var area;
 
  function toggleArea() {
        if(!area) {
                area = new nicEditor({fullPanel : true}).panelInstance('text',{hasPanel : true});
        } else {
                area.removeInstance('text');
                area = null;
        }
        x=document.getElementById('mqedit'); 
		if(x.style.display == "none") x.style.display = "block"; 
		else x.style.display = "none"
  }
//]]></script>

<a class="toggle_editor" href="javascript:toggleArea();">включит/отключить визуальный редактор</a>


<script>

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));

var countOfFields = 1;
var curFieldNameId = 1;
var maxFieldLimit = 10;

function sF(s) {
document.forms.postfo.elements.msubmit.value=" [ ] ";
document.forms.postfo.elements.msubmit.disabled="true";
if (s) document.forms.postfo.submit();
}

function deleteField(a) {
      var contDiv = a.parentNode;
      contDiv.parentNode.removeChild(contDiv);
      countOfFields--;
      return false;
}
function addField() {
      countOfFields++;
      curFieldNameId++;
      var div = document.createElement("div");
      div.innerHTML = "<input name=\"userfile[]\" type=\"file\" id=\"fi"+curFieldNameId+"\" /> <a onclick=\"return addField()\" href=\"#\">[+]</a> <a onclick=\"f_name_l('fi"+curFieldNameId+"')\" style=\"cursor: pointer;\">[^]</a> <a onclick=\"return deleteField(this)\" href=\"#\">[X]</a>";
      document.getElementById("files").appendChild(div);
      return false;
}

function mozz (str1,str2) {
  var ss = document.postfo.text.scrollTop;
  sel1 = document.postfo.text.value.substr(0, document.postfo.text.selectionStart);
  sel2 = document.postfo.text.value.substr(document.postfo.text.selectionEnd);

  sel = document.postfo.text.value.substr(document.postfo.text.selectionStart,
  document.postfo.text.selectionEnd - document.postfo.text.selectionStart);

  var text = document.postfo.text.firstChild;
  document.postfo.text.value = sel1 + str1 + sel + str2 + sel2;

  selPos = str1.length + sel1.length + sel.length + str2.length;
  document.postfo.text.setSelectionRange(sel1.length, selPos);
  document.postfo.text.scrollTop = ss;
}
function insert(str1,str2) {
if (is_nav) { mozz (str1,str2); }
frm = document.forms[0].text;
seltxt = frm.document.selection.createRange();
seltxt.text = str1+seltxt.text+str2;
document.forms[0].text.focus();
	}

function f_name_l(fileId)
  {
    var formFile = document.getElementById(fileId);
    file_name=formFile.value.replace(/^([^\\\/]*(\\|\/))*/,"");
    file_name = file_name.toLowerCase();
    is_img = ((file_name.indexOf('jpg')!=-1) || (file_name.indexOf('gif')!=-1) || (file_name.indexOf('png') != -1) || (file_name.indexOf('jpeg')!=-1) || (file_name.indexOf('tiff')!=-1) || (file_name.indexOf('tif')!=-1));
    if(is_img) { insert('<img src="<?=$_s['base_url'];?>/<?php e($_s['upload_dir']); ?>/'+file_name+'">',''); }
    else if(file_name!="") { insert('<a href="<?=$_s['base_url'];?>/<?php e($_s['upload_dir']); ?>/'+file_name+'">','<\/a>'); }
  }
</script> 						
<form method="post" name="postfo" enctype="multipart/form-data" id="add">

<table width="99%" align=center>
  <tr>
    <td>

<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td>
<input type="hidden" name="action" value="<?php e($_v['action']); ?>">
<input type="hidden" name="p" value="<?php e(@$_v['p']); ?>">
<input type="text" name="title" style="width:100%;" value="<?php e(@$post['title']); ?>">
    </td>
    <td bgcolor="#F8F7FB" width=1>
<div align="center" style="cursor: pointer;" onclick='document.getElementById("time").style.display = (document.getElementById("time").style.display == "block" ? "none" : "block")'><b>время/дата</b></div>
<div id="time" style="display: none; width:245px">
<input name="secu" type="hidden" value="<?php e(@$dt['sec']); ?>"><input name="hou" type="text" size="2" value="<?php e(@$dt['hou']); ?>" maxlength="2"><b>:</b><input name="minu" type="text" size="2" value="<?php e(@$dt['imp']); ?>" maxlength="2">, <input name="da" type="text" size="2" value="<?php e(@$dt['dau']); ?>" maxlength="2"> / <input name="mon" type="text" size="2" value="<?php e(@$dt['monh']); ?>" maxlength="2"> / <input name="yark" type="text" size="4" value="<?php e(@$dt['yar']); ?>" maxlength="4"><br>
<input type="checkbox" name="holdtime" <?php e(@$_s['htmchk']); ?>> <?php e($_l['pform_holdtime']); ?><br>
<input type="checkbox" name="dietime" <?php e(@$_s['dtmchk']); ?>> <?php e($_l['pform_dietime']); ?>
</div>
    </td>
  </tr>
</table>

<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td bgcolor=#F7F7F7><div id="mqedit">[<a style="cursor: pointer;" onclick="insert('&lt;b&gt;','&lt;/b&gt;')"><b>B</b></a>] [<a style="cursor: pointer;" onclick="insert('&lt;i&gt;','&lt;/i&gt;')"><i>I</i></a>] [<a style="cursor: pointer;" onclick="insert('&lt;u&gt;','&lt;/u&gt;')"><u>U</u></a>] [<a style="cursor: pointer;" onclick="insert('&lt;strike&gt;','&lt;/strike&gt;')"><strike>S</strike></a>] [<a style="cursor: pointer;" onclick="insert('&lt;a href=&quot;&quot;&gt;','&lt;/a&gt;')">link</a>] [<a style="cursor: pointer;" onclick="insert('&lt;img src=&quot;&quot; alt=&quot;&quot; border=&quot;0&quot;&gt;','')">img</a>] [<a style="cursor: pointer;" onclick="insert('&lt;cut&gt;','')">cut</a>] [<a style="cursor: pointer;" onclick="insert('&lt;center&gt;','&lt;/center&gt;')">center</a>] [<a style="cursor: pointer;" onclick="insert('&lt;div align=&quot;left&quot;&gt;','&lt;/div&gt;')">left</a>] [<a style="cursor: pointer;" onclick="insert('&lt;div align=&quot;right&quot;&gt;','&lt;/div&gt;')">right</a>] [<a style="cursor: pointer;" onclick="insert('&laquo;','&raquo;')">&laquo; &raquo;</a>] [<a style="cursor: pointer;" onclick="insert('&amp;lt;','')">&lt;</a>] [<a style="cursor: pointer;" onclick="insert('&amp;gt;','')">&gt;</a>] [<a style="cursor: pointer;" onclick="insert('&amp;quot;','')">&quot;</a>] [<a style="cursor: pointer;" onclick="insert('&amp;amp;','')">&amp;</a>] [<a style="cursor: pointer;" onclick="insert('&lt;br&gt;','')">BR</a>] [<a style="cursor: pointer;" onclick="insert('&amp;nbsp;','')">&amp;nbsp;</a>] [<a style="cursor: pointer;" onclick="insert('&lt;blockquote&gt;','&lt;/blockquote&gt;')">blockquote</a>] [<a style="cursor: pointer;" onclick="insert('&lt;font color=&gt;','&lt;/font&gt;')">font color</a>] [<a style="cursor: pointer;" onclick="insert('<table width=100% cellpadding=2 border=0>  <tr>    <td></td>    <td></td>  </tr>  <tr>    <td></td>    <td></td>  </tr></table>','')">table</a>] [<a style="cursor: pointer;" onclick="insert('&lt;h1&gt;','&lt;/h1&gt;')">H1</a>] [<a style="cursor: pointer;" onclick="insert('&lt;h2&gt;','&lt;/h2&gt;')">H2</a>] [<a style="cursor: pointer;" onclick="insert('&lt;h3&gt;','&lt;/h3&gt;')">H3</a>]  [<a style="cursor: pointer;" onclick="insert('&lt;h4&gt;','&lt;/h4&gt;')">H4</a>][<a style="cursor: pointer;" onclick="insert('&lt;code&gt;','&lt;/code&gt;')">code</a>]</div></td>
  </tr>
</table>

    </td>
    <td>
    <?php e($_s['uppostface']); ?>
    </td>
  </tr>
  <tr>
    <td>

<textarea name="text" id="text" style="width:100%; height:300px"><?php e(@$post['text']); ?></textarea>
    </td>
  </tr>
  <tr>
    <td>
<div align="center" style="cursor: pointer;" onclick='document.getElementById("extra").style.display = (document.getElementById("extra").style.display == "block" ? "none" : "block")'><b>опции/настройки</b></DIV>
<div id="extra" style="display: none">
<?php if($_v['action']!='add_page' && $_v['action']!='edit_page' && $_v['action']!='edit_comment') { ?>
<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td valign="top" colspan=2>
    <?php e($_s['dwnpostface_']); ?>
    </td>
  </tr>
  <tr>
    <td valign="top" width=50%>
<div align="center" style="background-color: #F8F8F8"><b><?php e($_l['pform_comments']); ?></b></div>
<input type="checkbox" name="closecomm" <?php e(@$_s['cmchk']); ?>> <?php e($_l['pform_commoff']); ?><br>
<input type="checkbox" name="commhide" <?php e(@$_s['prmchk']); ?>> <?php e($_l['pform_commhide']); ?><br>
<input type="checkbox" name="commpriv" <?php e(@$_s['prvchk']); ?>> <?php e($_l['pform_commpriv']); ?><br>
<input type="checkbox" name="subscrab" <?php e(@$_s['sbschk']); ?>> <?php e($_l['pform_subs']); ?><br>
<?php e($_l['pform_aattach']); ?>: <input name="attach" type="text" size=10 value="<?php e(@$post['attach']); ?>"> Kb<br>
    </td>
    <td valign="top">
    <b><?php e($_l['pform_options']); ?></b></div><br>
<input type="checkbox" name="close" <?php e($_s['clchk']); ?>><?php e($_l['pform_close']); ?><br>
<input type="checkbox" name="hidden" <?php e($_s['hdchk']); ?>><?php e($_l['pform_hide']); ?><br>
<input type="checkbox" name="nobr"<?php e($_s['brchk']); ?>><?php e($_l['nobr']); ?><br>
<?php e($_s['dwnpostface']); ?>
    </td>
  </tr>
</table>
</div>
<?php } ?>
<div align="center" style="background-color: #F8F8F8"><b><?php e($_l['pform_uplod']); ?></b></div>
<div id="files" align="center"><input name="userfile[]" type="file" id="fi1" /> <a onclick="return addField()" href="#">[+]</a> <a onclick="f_name_l('fi1')" style="cursor: pointer;">[^]</a> <a href="#"><font color="#C0C0C0">[X]</font></a>
</div>
</div>
</div>
<br>

<center><input name="msubmit" type="submit" value=" <?php e($_l['pform_send']); ?> [Ctrl+Enter] "  style="width:100%;height:25px;font-weight:bold;" ></center>

    </td>
  </tr>
</table>

</form>
<script language="JavaScript">
  <!--
    if (document.forms.postfo.elements.text.focus)
         document.forms.postfo.elements.text.focus();
         function ctrlenter(k)
          {
            if (k)
             {
               ctrl=k.ctrlKey;
               k=k.which;
             }
            else
             {
               k=event.keyCode;
               ctrl=event.ctrlKey;
             }
            if ((k==13 && ctrl) || (k==10)) sF(true);
          }
          e=document.forms.postfo.text;
          if (!document.all) document.captureEvents(Event.KEYDOWN)
          e.onkeydown=ctrlenter;
        //-->
</script>

