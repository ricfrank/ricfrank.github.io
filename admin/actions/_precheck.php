<?php
 error_reporting(E_ALL); ini_set('display_errors', 'on'); define('SUPPORT_EMAIL', 'support@cms-guide.com'); if (!isset($testing_from)) $testing_from = ""; $helpLinks = array( 'PHP_EXTENSION_LOADED' => 'https://support.cms-guide.com/hc/en-us/articles/203437214', 'FILE_WRITEBLE' => 'https://support.cms-guide.com/hc/en-us/articles/203437224', 'PHP_SAFE_MODE' => 'https://support.cms-guide.com/hc/en-us/articles/203437234', 'PHP_SESSION_CREATE' => 'https://support.cms-guide.com/hc/en-us/articles/203620920', 'PHP_EXTENSION_LOADED_ZEND' => 'https://support.cms-guide.com/hc/en-us/articles/203437424', 'Zend_Optimizer_version' => 'https://support.cms-guide.com/hc/en-us/articles/203437244', 'Files_corrupted' => 'https://support.cms-guide.com/hc/en-us/articles/203620930', 'Error_while_checking_admin_gateway' => 'https://support.cms-guide.com/hc/en-us/articles/203433184', ); foreach($helpLinks as &$link) { $link = '[ <a href="' . $link . '" target="_blank">Check for a possible solution here.</a> ]'; } $checker = new preInstall($testing_from); $checker->addValidator("FILE_WRITEBLE", array( "name" => "admin/logs", "type" => "Folder", "required" => true, 'fail_message' => 'ERROR: %type% <b>%name%</b> not writable.<br/>
		Please check permissions on this folder. ' . $helpLinks['FILE_WRITEBLE'], 'fail_message_1' => 'ERROR: %type% <b>%name%</b> doesn\'t exist.<br/>
		Please create it and set correct permissions. ' . $helpLinks['FILE_WRITEBLE'], ) ); $x = unserialize(''); $checker->addValidator("FILE_WRITEBLE", array( "name" => "admin/_tmp", "type" => "Folder", "required" => true, 'fail_message' => 'ERROR: %type% <b>%name%</b> not writable.<br/>
		Please check permissions on this folder. ' . $helpLinks['FILE_WRITEBLE'], 'fail_message_1' => 'ERROR: %type% <b>%name%</b> doesn\'t exist.<br/>
		Please create it and set correct permissions. ' . $helpLinks['FILE_WRITEBLE'], ) ); $checker->addValidator("FILE_WRITEBLE", array( "name" => "admin/_tmp/cache", "type" => "Folder", "required" => true, 'fail_message' => 'ERROR: %type% <b>%name%</b> not writable.<br/>
		Please check permissions on this folder. ' . $helpLinks['FILE_WRITEBLE'], 'fail_message_1' => 'ERROR: %type% <b>%name%</b> doesn\'t exist.<br/>
		Please create it and set correct permissions. ' . $helpLinks['FILE_WRITEBLE'], ) ); $checker->addValidator("FILE_WRITEBLE", array( "name"=>"admin/logs/moto.log", "type"=>"File", "required" => false, 'fail_message' => 'ERROR: %type% <b>%name%</b> not writable.<br/>
		Please check permissions on this file. ' . $helpLinks['FILE_WRITEBLE'], 'fail_message_1' => 'ERROR: %type% <b>%name%</b> doesn\'t exist.<br/>
		Please create it and set correct permissions. ' . $helpLinks['FILE_WRITEBLE'], ) ); $checker->addValidator("PHP_VERSION", array( "min"=>"5.2.1", 'fail_message' => 'ERROR: Your PHP version is "%php_version%", but "%min%" is required.<br />
		Please consult your hosting provider for assistance.', ) ); $checker->addValidator("PHP_SESSION_CREATE", array( 'fail_message' => 'ERROR: Server can not start session.<br />
		Please verify that your server can create sessions. ' . $helpLinks['PHP_SESSION_CREATE'], ) ); $checker->addValidator("PHP_CLASS_EXISTS", array( 'name' => 'DOMDocument', 'fail_message' => 'ERROR: Instance of "%name%" can not be created.<br />
          Please verify that all required libraries are enabled.', ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'gd', 'fail_message' => 'WARNING: <b>PHP extension "%name%" is not loaded.</b><br >
          Please verify your server configuration. Make sure that extension "%name%" is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], "level_error" => "WARNING", ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'curl', 'fail_message' => 'ERROR: PHP extension <b>"%name%"</b> is not loaded.<br >
          Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'mcrypt', 'fail_message' => 'ERROR: PHP extension <b>"%name%"</b> is not loaded.<br >
          <i>Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled.</i> ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'reflection', 'fail_message' => 'ERROR: PHP extension "%name%" is not loaded.<br >
          Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'dom', 'fail_message' => 'ERROR: PHP extension "%name%" is not loaded.<br >
          Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'spl', 'fail_message' => 'ERROR: PHP extension "%name%" is not loaded.<br >
          Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("PHP_SAFE_MODE", array( 'fail_message' => "WARNING: We have detected that PHP's Safe Mode is enabled on your server. We recommend turning it off for our software to work normally. You can disable safe mode in your hosting control panel or by editing a php.ini file. " . $helpLinks['PHP_SAFE_MODE'], 'level_error' => 'WARNING', ) ); $checker->addValidator("PHP_EXTENSION_LOADED", array( 'name' => 'ctype', 'fail_message' => 'ERROR: PHP extension <b>"%name%"</b> is not loaded.<br >
          Please verify your server configuration. Make sure that extension <b>"%name%"</b> is enabled. ' . $helpLinks['PHP_EXTENSION_LOADED'], ) ); $checker->addValidator("FILE", array( 'filename' => "admin/gateway.php|^Zend Amf Endpoint", 'error_txt' => '', 'valid_string' => '', 'fail_message' => 'ERROR: There is an error while checking %filename%.' . $helpLinks['Error_while_checking_admin_gateway'], 'fail_message_0' => 'ERROR: Unable to start file validation.', 'fail_message_1' => 'ERROR: File "%filename%" not found. Please upload files again.' . $helpLinks['Files_corrupted'], 'fail_message_2' => 'ERROR: There is an error while checking <u>%filename%</u>:<br />%error_txt%', 'fail_message_3' => 'ERROR: Zend Optimizer is not installed. ' . $helpLinks['PHP_EXTENSION_LOADED_ZEND'], 'fail_message_4' => 'ERROR: Files were corrupted. Please upload files again in binary mode. ' . $helpLinks['Files_corrupted'], 'fail_message_5' => 'ERROR: Your Zend Optimizer version is not sufficient to run CMS.<br>Please install Zend Optimizer 3.3 or higher. ' . $helpLinks['Zend_Optimizer_version'], 'fail_message_6' => 'ERROR: Cannot resolve your hostname: seems like your domain is not parked properly yet. If this is the only error you see here, please rename admin/actions/precheck.php file and reload this page.', 'fail_message_7' => 'ERROR: Your website files are not supported by Zend Guard Loader installed on your server. ' . $helpLinks['Zend_Optimizer_version'], ) ); $checker->addValidator("PHP_FUNCTION_EXISTS", array( 'name' => 'session_name', 'fail_message' => 'ERROR: PHP function "%name%" doesn\'t exist', ) ); $checker->addValidator("PHP_FUNCTION_EXISTS", array( 'name' => 'session_start', 'fail_message' => 'ERROR: PHP function "%name%" doesn\'t exist', ) ); $checker->addValidator("PHP_FUNCTION_EXISTS", array( 'name' => 'json_decode', 'fail_message' => 'ERROR: PHP function "%name%" doesn\'t exist', ) ); class preInstall { var $error = false; var $is_valid = true; var $errors = array(); var $validators=array(); var $base_url = ""; var $base_dir = ""; var $protocol = "http"; var $CURL_ERROR_CODE = array( 'CURLE_OK', 'CURLE_UNSUPPORTED_PROTOCOL', 'CURLE_FAILED_INIT', 'CURLE_URL_MALFORMAT', 'CURLE_URL_MALFORMAT_USER', 'CURLE_COULDNT_RESOLVE_PROXY', 'CURLE_COULDNT_RESOLVE_HOST', 'CURLE_COULDNT_CONNECT', 'CURLE_FTP_WEIRD_SERVER_REPLY', ); function __construct($from = "") { $self = ( !empty($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME') ); if (empty($self)) $self = (!empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF')); if (empty($self)) { $self = str_replace("\\", "/", __FILE__); $self = explode($_SERVER["DOCUMENT_ROOT"], $self); $self = "/" . $self[1]; } $dir = preg_replace("/[\/\\\]+/i", "/", dirname($self) . "/"); if (substr($dir, 0, 1) == ".") $dir = substr($dir , 1); if ($from != "") { if (preg_match('/^(.*)\/('.$from.')\/$/', $dir, $regs)) { $dir = $regs[1]."/"; } } if (isset($_SERVER["SERVER_PROTOCOL"])) { if (preg_match("/^([^\/]*)\//i", $_SERVER["SERVER_PROTOCOL"], $regs)) { $this->protocol = strtolower($regs[1]); } } $this->base_url = $this->protocol . "://" . ( !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME') ) . $dir; $this->base_dir = str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../' ))."/"; return $this; } function _separatorToCamelCase($subject, $separator = '_') { $separator = preg_quote($separator, '\\'); $parts = explode($separator, strtolower($subject)); $parts = array_map('ucfirst', $parts); return implode($parts); } function addValidator($name,$params=array()) { $this->validators[] = array($name,$params); } function validatePhpVersion($params) { $min = isset($params['min']) ? $params['min'] : 0; $max = isset($params['max']) ? $params['max'] : 0; $params['php_version'] = PHP_VERSION; if ($min > 0) { $params['is_valid'] = $params['is_valid'] && version_compare(PHP_VERSION, $min, '>='); } if ($max > 0) { $params['is_valid'] = $params['is_valid'] && version_compare(PHP_VERSION, $max, '<='); } return $params; } function validatePhpSessionPath($params) { $params["session_save_path"] = session_save_path(); if ((strtolower(ini_get("session.save_handler")) == "files") && (!is_dir(session_save_path()))) { $params["is_valid"] = false; } return $params; } function validatePhpUploadTmpDir($params) { $params["upload_tmp_dir"] = ini_get("upload_tmp_dir"); if ( $params["upload_tmp_dir"] =="" || !is_dir($params["upload_tmp_dir"]) || !is_writable($params["upload_tmp_dir"])) { $params["is_valid"] = false; } return $params; } function validateFileWriteble($params) { $params['is_valid'] = true; if (strtoupper($params['type']) == 'FOLDER') { if ( !is_dir($this->base_dir . $params["name"]) ) { if (!isset($params['required']) || $params['required']) { $params['is_valid'] = false; $params['fail_message'] = $params['fail_message_1']; return $params; } } else $params['is_valid'] = is_writable($this->base_dir . $params['name']); } elseif (strtoupper($params['type']) == 'FILE') { if ( !is_file($this->base_dir . $params["name"]) ) { if (!isset($params['required']) || $params['required']) { $params['is_valid'] = false; $params['fail_message'] = $params['fail_message_1']; return $params; } } else $params['is_valid'] = is_writable($this->base_dir . $params['name']); } else $params['is_valid'] = is_writable($this->base_dir . $params['name']); return $params; } function validatePhpSessionCreate($params) { if (!function_exists('session_start') || !function_exists('session_name') ) { $params["is_valid"] = false; return $params; } session_start(); if(!isset($params["test_key"])) $params["test_key"]="Test_session_key_" . time(); if(!isset($params["test_value"])) $params["test_value"]="Test_session_value_" . time(); $_SESSION[$params["test_key"]]=$params["test_value"]; session_write_close(); $_SESSION=array(); session_start(); if (!isset($_SESSION[$params["test_key"]])) $params["is_valid"] = false; else unset($_SESSION[$params["test_key"]]); session_write_close(); return $params; } function validatePhpExtensionLoaded($params) { $is_valid = false; if (!isset($params['name'])) $params['name'] = $params['extension']; if (strpos($params['name'], '|') > 0) { $extensions = explode('|', $params['name']); foreach($extensions as $extension ) { $is_valid = $is_valid || extension_loaded($extension); } } else { $is_valid = extension_loaded($params['name']); } $params['is_valid'] = $is_valid; return $params; } function validatePhpFunctionExists($params) { if (!isset($params['name'])) $params['name']=$params['function']; $params['is_valid'] = function_exists($params['name']); return $params; } function validatePhpSafeMode($params) { $safe_mode = ini_get('safe_mode'); $params['is_valid'] = !( $safe_mode == true || strtolower($safe_mode) == 'on' ); return $params; } function validatePhpClassExists($params) { if (!isset($params['name'])) $params['name']=$params['class']; $params['is_valid'] = class_exists($params['name']); return $params; } function validateFile($params) { if (!$this->is_valid) return $params; if (!extension_loaded('curl') || version_compare(PHP_VERSION, "5.2.1", "<")) { return array("is_valid" => false, "fail_message" => $params["fail_message_0"] ); } $params["is_valid"] = true; $is_error = 0; $verif_string = array(); if (preg_match("/^([^\|]*)[\|]([^\|]*)([\|](.*))?$/", $params["filename"], $regs)) { $verif_string["ok"] = $regs[2]; if (isset($regs[4])) $verif_string["error"] = $regs[4]; $params["filename"] = $regs[1]; } $url = $this->base_url . $params["filename"]; if (preg_match('/(\.dev|\.loc)/', $this->base_url)) { return $params; } if (!file_exists($this->base_dir . $params["filename"])) { $is_error = 1; } else { $ch = curl_init(); curl_setopt($ch, CURLOPT_URL, $url); curl_setopt($ch, CURLOPT_HEADER, 0); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); if ($this->protocol == "https") { curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); } curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); $str = curl_exec($ch); if (curl_errno($ch)) $is_error = 6; if ($is_error == 0 && !$this->is_goodAnswerFile($str, $verif_string)) { $is_error = 2; $params["error_txt"] = strip_tags($str); if (!$this->is_goodAnswerFile($str, array("error"=>"Zend Optimizer not installed"))) { $is_error = 3; } if (!$this->is_goodAnswerFile($str, array("error"=>"Incompatible file format:[ ]+The encoded file has format major ID"))) { $is_error = 5; if ( version_compare(PHP_VERSION, "5.3", ">=") && extension_loaded('Zend Guard Loader')) { $is_error = 7; } } elseif (!$this->is_goodAnswerFile($str)) { $is_error = 4; } } } if ($is_error) { if ($is_error == 6 && !isset($params["fail_message_" . $is_error])) { $params["fail_message_" . $is_error] = "ERROR: <b>PHP curl</b> reported an error (" . curl_errno($ch); if (curl_error($ch) != "") $params["fail_message_" . $is_error] .= " - " . curl_error($ch); elseif(isset($this->CURL_ERROR_CODE[curl_errno($ch)])) $params["fail_message_" . $is_error] .= " - " . $this->CURL_ERROR_CODE[curl_errno($ch)]; $params["fail_message_" . $is_error] .= ")"; } if (isset($params["fail_message_" . $is_error])) $params["fail_message"] = $params["fail_message_" . $is_error]; $params["is_valid"] = false; } return $params; } function run() { foreach($this->validators as $validator) { list($validator, $params) = $validator; $validator = 'validate' . $this->_separatorToCamelCase($validator); if (!method_exists($this, $validator)) { trigger_error('Call to undefined validator "' . $validator . '"', E_USER_ERROR); return false; } $params["is_valid"] = true; $result = call_user_func(array(&$this, $validator), $params); if (!isset($params["level_error"]) || $params["level_error"] != "WARNING") $this->is_valid = $this->is_valid && $result['is_valid']; if (!$result['is_valid']) { $message=""; if (isset($result['fail_message'])) { unset($result["list"]); $vars = explode(',', '%' . implode("%,%", array_keys($result)) . '%'); $values = array_values($result); $message = str_replace($vars, $values, $result['fail_message']); } $this->errors[]=$message; } } if ($this->isValid()) { $this->errors[] = "OK: Preliminary requirements check passed.<br>"; $this->finish(); } else { $this->showErrors(); } } function finish() { if (!$this->isValid()) return false; if ( file_exists(dirname(__FILE__) . '/_' . basename(__FILE__)) ) { @rename( dirname(__FILE__) . '/_' . basename(__FILE__) , dirname(__FILE__) . '/_' . uniqid() . '_' . basename(__FILE__) ); } @rename(__FILE__, dirname(__FILE__) . '/_' . basename(__FILE__)); if (file_exists(__FILE__)) { $this->errors[] = "Warning:" . "Please delete or rename file <b>" . $this->base_url . str_replace($this->base_dir, "", str_replace("\\", "/", __FILE__)) . "</b> to continue installation."; $this->showErrors(); exit(); } } function isValid() { return $this->is_valid; } function showErrors($print = 1) { $errors = array(); for($ierror = 0; $ierror < count($this->errors); $ierror++) { if (is_array($this->errors[$ierror])) { for($i = 0; $i < count($this->errors[$ierror]); $i++) $errors[] = $this->returnError($this->errors[$ierror][$i]); } else { $errors[] = $this->returnError($this->errors[$ierror]); } } ?>
<html>
<head>
<link href="<?php echo $this->base_url ?>admin/actions/assets/style.css" rel="stylesheet" type="text/css" />
<style>
* {
	margin:0;
	padding:0;
}
html, body {
	height:100%;
}
html {
	min-width:980px;
}
body {
	background:white;
	font-family:Arial, Helvetica, sans-serif;
	font-size:100%;
	line-height:1em;
	color:#393939;
}

img {
	border:0;
	vertical-align:top;
	text-align:left;
}
object {
	vertical-align:top;
	outline:none;
}
ul, ol {
	list-style:none;
}
table, table td {
	padding:0;
	border:none;
	border-collapse:collapse;
}
#main {
	width:980px;
	margin:0 auto;
	font-size:.75em;
	padding:150px 0 0 0;
}
a {
	color:#d52d00;
	outline:none;
}
a:hover{
	text-decoration:none;
}

/*==================boxes====================*/
.box {
	background:url(<?php echo $this->base_url ?>admin/images/box-bg.gif) 0 0 repeat-y;
	width:719px;
}
.box .top-bg {
	background:url(<?php echo $this->base_url ?>admin/images/box-top-bg.gif) no-repeat 0 0;
}
.box .bot-bg {
	background:url(<?php echo $this->base_url ?>admin/images/box-bot-bg.gif) no-repeat 0 100%;
}
.list1 {
}
.list1 li {
	position:relative;
	border-bottom:1px solid #dedede;
	padding:20px 30px 20px 60px;
	min-height:45px;
	height:auto !important;
	height:45px;
	zoom:1
}
.list1 li img {
	position:absolute;
	left:17px;
	top:20px;
}
</style>
</head>
<body>
<br/>
<div class="main" id="main-body" style="width: 786px; margin: auto; ">
<div id="body_header" style="
background:url('<?php echo $this->base_url ?>admin/images/header.png') no-repeat;
height:50px;font-family: arial;
font-size: 22px;
">
<div style="padding-top: 20px; margin-left:32px;

">CMS Server Tester</div>
</div>
<div id="body_content" style="
background:url('<?php echo $this->base_url ?>admin/images/content.png');
height: auto;
font-size: 12px; font-family: arial;
">
<div style="margin-left:30px; margin-right:35px; width:720px;">

	<div style="padding-top:12px;padding-bottom: 17px;">
	Your server was tested to meet all CMS requirements. Following errors were encountered an need to be fixed to run the software:
	</div>


    <!-- .box -->
    <div class="box">

    	<div class="top-bg">
      	<div class="bottom-bg">
        	<ul class="list1">
        	<?php foreach($errors as $error) {?>
          	<li>
            	<img src="<?php echo $this->base_url ?>admin/images/<?php echo $error['type'] ?>.jpg" alt="" />
            	<?php echo $error['message'] ?>
            </li>
            <?php }?>

          </ul>
        </div>
      </div>
    </div>

    <!-- /.box -->

	<div style="padding-top:12px;padding-bottom: 17px;">
	Please contact your hosting provider for guidelines on server configuration. You can mail our support team  <a href="mailto:<?php echo SUPPORT_EMAIL?>"><?php echo SUPPORT_EMAIL?></a> for additional information.
	</div>

</div>


</div>
<div id="body_footer" style="
background:url('<?php echo $this->base_url ?>admin/images/footer.png') no-repeat;
height:10px;
"></div>


</div>
</body>
</html>
<?php
 exit; } function returnError($data) { $error = array('type' => 'error', 'message' => '', 'description' => null ); preg_match('/(^[\w\-_]+):(.*)$/ism', $data, $message); $message[1] = isset($message[1]) ? $message[1] : 'error'; $message[2] = isset($message[2]) ? $message[2] : "Validation failed"; $message[3] = isset($message[3]) ? $message[3] : ''; $error['type'] = strtolower($message[1]); $error['message'] = $message[2]; if (strlen($message[3]) > 0) $error['description'] = $message[2]; return $error; } function showError($error) { preg_match('/(^[\w\-_]+):(.*)$/ism', $error, $message); $message[1] = isset($message[1]) ? $message[1] : 'error'; $message[2] = isset($message[2]) ? $message[2] : "Validation failed"; $message[3] = isset($message[3]) ? $message[3] : ''; echo "<tr><td>"; if (strtoupper($message[1]) == "WARNING") echo "<img src='" . $this->base_url . "admin/images/warning.png'>"; else echo "<img src='" . $this->base_url . "admin/images/error.jpg'>"; echo "</td><td>$message[2]"; if (strlen($message[3]) > 0) echo "<br /><i>$message[3]</i>"; echo "</td></tr>\n"; } function is_goodAnswerFile($str, $params = array()) { $result = true; $str = strip_tags($str); if ( (!isset($params["error"])) && (!isset($params["ok"]) || $params["ok"] == "") ) { $params["error"] = "Fatal error:(.*)(Incompatible file format|Corrupted encoded data detected|This encoded file is corrupted|Unable to read)"; } if (isset($params["ok"]) && $params["ok"] != "") { $result = false; if (preg_match("/".$params["ok"]."/i", $str)) { $result = true; } } if ( (isset($params["error"])) && ($params["error"] != "") && (preg_match("/".$params["error"]."/i", $str)) ) { $result = false; } return $result; } } $checker->run(); unset($checker); ?>