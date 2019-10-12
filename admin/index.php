<?php
if (file_exists("./actions/precheck.php")) { $testing_from = "admin"; require_once "./actions/precheck.php"; } require_once 'config/ProjectConfig.php'; ProjectConfig::registerAutoload(); ProjectConfig::loadConstants(); ProjectConfig::startSession(); if (file_exists("./actions/update/index.php")) { if (MotoVersion::isNeedUpdate()) { header('Location: actions/update/'); exit; } } $config_xml = new MotoXML(CONTROL_PANEL_CONFIGURATION); $result = MotoXML::findOneByXPath(".//item[@name='extendedRightClickMenu']", $config_xml); $extendedRightClickMenu = !empty($result) && $result->nodeValue == 'true'; $user = new UserService(); $info = ContentService::getProductInformation(); $updateRequest = 'product_type=html'; $updateRequest .= '&product_id=' . rawurlencode($info['product_id']); $updateRequest .= '&template_id=' . $info['template_id']; $updateRequest .= '&version=' . CONTROL_PANEL_VERSION; $updateRequest .= '&php=' . rawurlencode(substr(PHP_VERSION, 0, 3)); $updateRequest .= '&host=' . rawurlencode(empty($_SERVER['SERVER_NAME']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']); $updateRequest .= '&logged=' . ($user->isAuthenticated()->status->status * 1); $updateRequest .= '&time=' . time(); $checkUpdateUrl = 'http://accounts.cms-guide.com/check-updates/html/?' . $updateRequest; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo CONTROL_PANEL_COMPANY_NAME;?></title>
	<?php if (file_exists('./favicon.ico')) {?>
		<link  rel="SHORTCUT ICON" href="./favicon.ico" type="image/vnd.microsoft.icon" />
	<?php } ?>
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="swfobject.js"></script>
	<script type="text/javascript" src="rightClick.js"></script>

	<script type="text/javascript">
	
		function swfObjectCallback()
		{
			var js = document.createElement('script');
			js.type = 'text/javascript';
			js.async = true;
			js.src = 'http://connect.facebook.net/en_US/all.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(js);
		}
	
		var params = {
<?php if ($extendedRightClickMenu): ?>
			wmode: "opaque",
<?php endif; ?>
			menu: "false",
			allowScriptAccess: "always",
			scale: "noscale",
			allowFullScreen: "true"
		};
		var attributes = {
			id: "ControlPanel",
			name: "ControlPanel"
		};
		swfobject.embedSWF("ControlPanel.swf?version=<?php echo CONTROL_PANEL_VERSION ?>", "flashcontent", "100%", "100%", "9.0.23", "expressInstall.swf", false, params, attributes, swfObjectCallback);
		SWFID = "ControlPanel";

		function openAuthenticateWindow(url)
		{
			authenticationWindow = window.open(url, "authenticationWindow",
				"width=720, height=500, resizable=yes, scrollbars=yes, status=yes");
		}

		function checkUpdates() {
			try {
				var js = document.createElement('script');
				js.type = 'text/javascript';
				js.async = true;
				js.src = '<?php echo $checkUpdateUrl;?>';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(js);
			} catch (e) {}
		}
		checkUpdates();

	</script>
</head>

<body <?php if ($extendedRightClickMenu): ?>onLoad="RightClick.init();"<?php endif; ?>>
<!-- HOOK_INJECTOR:DEMO_ADMIN_BAR -->
	<div id="fb-root"></div>
	<div id="flashcontent">
		<br /><br /><a href="<?php echo CONTROL_PANEL_COMPANY_URL;?>" target="_blank"><?php echo CONTROL_PANEL_COMPANY_NAME;?></a>
		<br /><br /><strong>Please update your Flash Player</strong><br /><br />
		This site makes use of the Adobe Flash Player.<br /><br />
		The latest versions of browsers such as Firefox, Netscape or Internet Explorer usually have the Flash Player pre-installed.<br /><br />
		If your browser doesn't or has an older version of the player, you can <a href="http://www.adobe.com/go/getflashplayer" target="_blank"><b>download it here</b></a>.<br /><br />
		Flash Player enables us to provide you with a dynamic website with video clips and full screen images.
	</div>
</body>
</html>
