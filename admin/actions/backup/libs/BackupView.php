<?php
if ( !function_exists('json_encode') ) { function json_encode( $string ) { require_once( 'class-json.php' ); $json = new Services_JSON(); return $json->encodeUnsafe( $string ); } } class BackupView { private $_data = array(); private $base_url = ''; private $base_dir = ''; public $resultAction = array(); public $_log = array(); public $logs = array(); public $errors = array(); function __construct() { $this->title = Messages::get('I_WINDOW_TITLE'); } function template($tmpl, $data) { $vars = explode(',', '%' . implode("%,%", array_keys($data)) . '%'); $values = array_values($data); return str_replace($vars, $values, $tmpl); } function render() { if ($this->output == 'json') $this->render_json(); $this->render_layout(); } function render_json() { $method = '_json' . ucfirst(strtolower($this->action)) . ucfirst(strtolower($this->sub)); if (method_exists($this, $method)) $this->$method(); $data = $this->resultAction; $data['ok'] = (isset($data['ok']) ? $data['ok'] * 1 : 0); $data['time'] = time(); $data['method'] = $method; echo json_encode($data); exit; } function render_menu() { return ; } function render_content() { $method = '_content' . ucfirst(strtolower($this->sub)); if (method_exists($this, $method)) $this->$method(); } function render_log($errors = array()) { ?>
			<div class="top-bg">
				<div class="bottom-bg">
					<ul id="mainList" class="list1">
					<?php
 foreach($errors as $error) {?>
						<li>
							<img src="<?php echo $this->base_url ?>assets/<?php echo $error['type'] ?>.jpg" alt="" />
							<?php echo $error['message'] ?>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
<?php
 } function _contentBackup() { $messages = $this->Messages; ?>
    	<div class="top-bg">
      	<div class="bottom-bg" id="_workplace" style="zdisplay:none;">
        	<ul class="list1" id="workplace" >

<?php if (isset($messages['log'])) foreach($messages['log'] as $key=>$message) {?>
          	<li>
            	<img src="assets/<?php echo $message['type'] ?>.jpg" alt="" />
            	<?php echo $message['message'] ?>
            </li>
<?php }?>

<?php
if ($this->ok) { ?>
	<li id="" style="display:none;">
	<div id="backup_body">
<div id="backup_cp" class="backup_step">
<b>[</b> <a href="#" onclick="Backup.init();return false;"><?php echo Messages::get('I_CREATE_BACKUP')?></a> <b>]</b>
</div>

<div id="backup_config" class="backup_step">

<form id="FBConfig" method="post">
<div>
<table class="steps">
<?php
if (isset($this->backupTypeAllow) && is_array($this->backupTypeAllow)) { $checked = 'checked'; foreach($this->backupTypeAllow as $type) { ?>
<tr valign="top">
	<td width="30px" align="left" style="padding-top:6px;">
		<input name="mode" value="<?php echo strtolower($type)?>" type="radio" <?php echo $checked?> />
	</td>
	<td class="step"><?php echo Messages::get('BACKUP_TYPE_' . strtoupper($type));?></td>
</tr>
<?php
 $checked = ''; } } ?>
</table>
</div>
<br/>
<div>
<button onClick="Backup.start();return false;"><?php echo Messages::get('BUTTON_START')?></button>
<button onClick="Backup.close();return false;"><?php echo Messages::get('BUTTON_CLOSE')?></button>
</div>
</form>



</div>

<div id="backup_list" class="backup_step" style="zdisplay:none;">
<b>[</b> <a href="#" onclick="backupCreate();return false;"><?php echo Messages::get('I_CREATE_BACKUP')?></a> <b>]</b>
</div>

	</div>
	</li>

	<li style="display:none;" class="backup_start_items backup_step">

<div id="backup_wait" class="backup_step" style="padding-bottom: 8px;padding-top: 8px;"><?php echo Messages::get('JS_START_WAIT')?></div>

<div id="backup_work" class="backup_step" style="zdisplay:none;">
</div>
<div id="backup_work_cp" class="backup_step" style="zdisplay:none;">
<!--  <button onClick="Backup.close();return false;"><?php echo Messages::get('BUTTON_CLOSE')?></button>  -->
</div>
	</li>

<?php
} ?>

<?php if (isset($messages['_footer'])) foreach($messages['_footer'] as $key=>$message) {?>
          	<li>
            	<img src="assets/<?php echo $message['type'] ?>.jpg" alt="" />
            	<?php echo $message['message'] ?>
            </li>
<?php }?>

          </ul>
        </div>
      </div>

<script type="text/javascript">
<!--
if (!Backup)
	var Backup = null;
var js_template = new Array();
js_template['showlist'] = '<table>{showlist_row}</table>';
js_template['showlist_row'] = '<tr><td>{n}</td><td>{filename}</td><td>{filesize}</td><td>{created}</td></tr>';

$(document).ready(function()
{
	Backup = new _backup('Backup');
	Backup.reset();
});
//-->
</script>
      <?php
 } function _jsonInitstartBackup() { } function render_layout() { ?>
<html>
<head>
<title><?php echo $this->title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo $this->base_url ?>assets/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->base_url ?>assets/backup.js"></script>
<script type="text/javascript" src="<?php echo $this->base_url ?>assets/jquery.min.js"></script>

</head>
<body>
<br/><br/>
<div class="main" id="main-body" style="width: 786px; margin: auto; ">
<div id="body_header" style="
background:url('<?php echo $this->base_url ?>assets/header.png') no-repeat;
height:50px;font-family: arial;
font-size: 22px;
">
<div style="padding-top: 20px; margin-left:32px;"><?php echo Messages::get('I_TITLE')?></div>
</div>
<div id="body_content" style="
background:url('<?php echo $this->base_url ?>assets/content.png');
height: auto; font-size: 12px; font-family: arial;
">
<div style="margin-left:30px; margin-right:35px; width:720px;">

	<div style="padding-top:12px;padding-bottom: 17px;">
	<?php echo Messages::get('I_HEADER')?>

	</div>

	<?php $this->render_menu();?>
		<!-- .box -->
		<div class="box">
<?php $this->render_content() ?>
<!--
 -->
		</div>

		<!-- /.box -->

	<div style="padding-top:12px;padding-bottom: 17px;">
	<?php ?>
<?php
if ($this->ok) { ?>
	<div style="text-align:center;padding-top:0px;" class="backup_step backup_cp">
	<a href="#" onclick="Backup.start();return false;"><img src="assets/start_backup.png" width="185px"" height="33px"/></a>
	<br/>
	<br/>
	</div>
	<?php
} ?>	</div>

</div>


</div>
<div id="body_footer" style="
background:url('<?php echo $this->base_url ?>assets/footer.png') no-repeat;
height:10px;
"></div>


</div>

</body>
</html>
<?php
 exit; } function __set($key, $value = '') { if (isset($this->$key) && $key[0] != '_') $this->$key = $value; else $this->_data[$key] = $value; } function __get($key) { if (isset($this->$key) && $key[0] != '_') return $this->$key; elseif (isset($this->_data[$key])) return $this->_data[$key]; else return null; } function __isset($variable) { return false; } }