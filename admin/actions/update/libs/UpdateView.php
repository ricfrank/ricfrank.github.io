<?php
 if ( !function_exists('json_encode') ) { function json_encode( $string ) { require_once( 'class-json.php' ); $json = new Services_JSON(); return $json->encodeUnsafe( $string ); } } class UpdateView { private $_data = array(); private $base_url = ''; private $base_dir = ''; public $resultAction = array(); public $_log = array(); public $logs = array(); public $errors = array(); public $steps = array(); function __construct() { $this->title = Messages::get('I_WINDOW_TITLE'); } function template($tmpl, $data) { $vars = explode(',', '%' . implode("%,%", array_keys($data)) . '%'); $values = array_values($data); return str_replace($vars, $values, $tmpl); } function render() { if ($this->output == 'json') $this->render_json(); $this->render_layout(); } function _jsonUpdateFinish() { $data = $this->resultAction; $html = $this->template(Messages::get('N_STEP_FINISH_INFO', array('MOTO_ROOT_URL'=>MOTO_ROOT_URL)), $data); if ($data['ok']) $img = '<img src="assets/ok.jpg"/>'; else $img = '<img src="assets/error.jpg"/>'; $this->resultAction['html'] = $html; $this->resultAction['img'] = $img; } function _jsonUpdateCopying() { $data = $this->resultAction; $html = ''; if (isset($this->_log['log'])) foreach($this->_log['log'] as $i => $log) { $html .= $this->template($log['message'], $data) . '<br/>'; } if ($data['ok']) $img = '<img src="assets/ok.jpg"/>'; else $img = '<img src="assets/error.jpg"/>'; $this->resultAction['html'] = $html; $this->resultAction['img'] = $img; } function _jsonUpdateUpdate() { $data = $this->resultAction; $html = ''; foreach($this->_log['log'] as $i => $log) { $html .= $this->template($log['message'], $data) . '<br/>'; } if ($data['ok']) $img = '<img src="assets/ok.jpg"/>'; else $img = '<img src="assets/error.jpg"/>'; $this->resultAction['html'] = $html; $this->resultAction['img'] = $img; } function _jsonUpdateBackup() { $data = $this->resultAction; $img = $html = ''; if ($data['ok']) { $tmpl = ''; $tmpl .= Messages::get('N_STEP_BACKUP_INFO'); if ($data['zipSize'] > 0 && $data['tmpRemoved']) $tmpl .= Messages::get('N_STEP_BACKUP_DEL_TEMP'); $tmpl .= Messages::get('I_STEP_RUN_NEXT'); $img = '<img src="assets/ok.jpg"/>'; } else { $img = '<img src="assets/error.jpg"/>'; } $html = $this->template($tmpl, $data); $this->resultAction['html'] = $html; $this->resultAction['img'] = $img; } function render_json() { $method = '_json' . ucfirst(strtolower($this->sub)) . ucfirst(strtolower($this->step)); if (method_exists($this, $method)) $this->$method(); $data = $this->resultAction; $data['ok'] *= 1; $data['time'] = time(); $data['step'] = $this->step; $data['method'] = $method; echo json_encode($data); exit; } function render_menu() { return ; } function render_content() { $method = '_content' . ucfirst(strtolower($this->sub)); if (method_exists($this, $method)) $this->$method(); } function render_log($errors = array()) { ?>
			<div class="top-bg">
				<div class="bottom-bg">
					<ul id="mainList" class="list1">
					<?php foreach($errors as $error) {?>
						<li>
							<img src="<?php echo $this->base_url ?>assets/<?php echo $error['type'] ?>.jpg" alt="" />
							<?php echo $error['message'] ?>
						</li>
						<?php }?>

					</ul>
				</div>
			</div>
<?php
 } function _contentUpdate() { $messages = $this->UpdateMessages; ?>
    	<div class="top-bg">
      	<div class="bottom-bg">
        	<ul class="list1">

<?php if (isset($messages['_header'])) foreach($messages['_header'] as $key=>$message) {?>
          	<li>
            	<img src="assets/<?php echo $message['type'] ?>.jpg" alt="" />
            	<?php echo $message['message'] ?>
            </li>
<?php }?>
<?php  if (is_array($this->steps)) foreach($this->steps as $key=>$step) {?>
          	<li id="step_<?php echo $step['name']?>" style="display:none;">
          	<span id="step_<?php echo $step['name']?>_img">
          	<?php if (isset($step['img'])):?>
            	<img src="assets/<?php echo $step['img'] ?>.jpg" alt="" />
            <?php endif;?>
            </span>
            	<div><b><?php echo $step['title'] ?></b> <span id="step_<?php echo $step['name']?>_status"></span></div>
            	<div id="step_<?php echo $step['name']?>_info"></div>
            </li>
<?php }?>
<?php if (isset($messages['log'])) foreach($messages['log'] as $key=>$message) {?>
          	<li>
            	<img src="assets/<?php echo $message['type'] ?>.jpg" alt="" />
            	<?php echo $message['message'] ?>
            </li>
<?php }?>
<?php if (isset($messages['_footer'])) foreach($messages['_footer'] as $key=>$message) {?>
          	<li>
            	<img src="assets/<?php echo $message['type'] ?>.jpg" alt="" />
            	<?php echo $message['message'] ?>
            </li>
<?php }?>

          </ul>
        </div>
      </div>
      <?php
 } function render_layout() { ?>
<html>
<head>
<title><?php echo $this->title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?php echo $this->base_url ?>assets/update.js"></script>
<script type="text/javascript" src="<?php echo $this->base_url ?>assets/jquery.min.js"></script>

<style>
*, form {	margin:0;	padding:0;}
html, body {	height:100%;}
html {	min-width:980px;}
body {	background:white;	font-family:Arial, Helvetica, sans-serif;	font-size:100%;	line-height:1em;	color:#393939;}
img {	border:0;	vertical-align:top;	text-align:left;}
object {	vertical-align:top;	outline:none;}
table, table td {	padding:0;	border:none;	border-collapse:collapse;}
#main {	width:980px;	margin:0 auto;	font-size:.75em;	padding:150px 0 0 0;}
a {	color:#d52d00;	outline:none;}
a:hover{text-decoration:none;}
.menu a {text-decoration:none;}
.menu a:hover {text-decoration:underline;}
/*==================boxes====================*/
.box {	background:url(<?php echo $this->base_url ?>assets/box-bg.gif) 0 0 repeat-y;	width:719px; }
.box .top-bg {	background:url(<?php echo $this->base_url ?>assets/box-top-bg.gif) no-repeat 0 0;}
.box .bot-bg {	background:url(<?php echo $this->base_url ?>assets/box-bot-bg.gif) no-repeat 0 100%;}
.list1 {}
.box ul, .box ol {	list-style:none;}
.list1 li {
	position:relative;
	border-bottom:1px solid #dedede;
	padding:20px 30px 20px 60px;
	min-height:45px;
	height:auto !important;
	height:45px;
	zoom:1
}
.list1 li img {	position:absolute;	left:17px;	top:20px;}
.menu {
	background: #bbdd44;
	height:auto !important;
	min-height:30px;
	height:30px;
}
.menu ul, .menu ol {	list-style:none;}
.menu li:hover {	color: red;}
.menu li {
	float: left;
	position:relative;
	padding:0px 10px 0px 10px;
	zheight:auto !important;
	zmin-height:30px;
	zheight:30px;
}
.backup-wait{
    color:gray;
}
.backup-working{
    color: green;
}
.backup-finished{
    color:black;
}

</style>
<script type="text/javascript" >
<!--
var Messages = new Array();
<?php
$jsMessages = array('JS_START_UPDATE', 'JS_START_WAIT', 'JS_START_BACKUP', 'JS_E_ON_WORK_BACKUP'); foreach($jsMessages as $key) { echo "Messages['$key'] = '" . Messages::get($key) . "'\n"; } ?>
<?php
 echo 'var backup_need = ' . ($this->backupAllow*1) . ";\n"; ?>
-->
</script>
</head>
<body>
<br/><br/>
<form id="mainForm" name="mainForm" method="post">
<input name="action" value="runstep" type="hidden"/>
<input name="sub" value="update" type="hidden"/>
<input name="param" value="" type="hidden"/>
<input name="step" value="" type="hidden"/>
<input name="output" value="json" type="hidden"/>
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
	<?php echo Messages::get('I_FOOTER')?>
	</div>

</div>


</div>
<div id="body_footer" style="
background:url('<?php echo $this->base_url ?>assets/footer.png') no-repeat;
height:10px;
"></div>


</div>

</form>
</body>
</html>
<?php
 exit; } function __set($key, $value = '') { if (isset($this->$key) && $key[0] != '_') $this->$key = $value; else $this->_data[$key] = $value; } function __get($key) { if (isset($this->$key) && $key[0] != '_') return $this->$key; elseif (isset($this->_data[$key])) return $this->_data[$key]; else return null; } function __isset($variable) { return false; } }