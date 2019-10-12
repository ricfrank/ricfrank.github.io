<?php
/* HOOK_INJECTOR:DEMO_WEBSITE_LAYOUT_START */
?>
<!DOCTYPE html>
<html>
    <head>
		<title><?php $this->output('title') ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8;" />
		<meta name="viewport" content="width=<?php echo $content->website->width?>" />
		<meta name="format-detection" content="telephone=no">
<?php
/**
 * @var $basePath string
 * @var $websitePreloader string
 * @var $contentPreloader string
 * @var $page PageVO
 */
$style = $content->website->style;
if (isset($pageFavicon))
{
	foreach($pageFavicon as $favicon)
	{
		echo '<link '
			. (isset($favicon["rel"]) && $favicon["rel"]!="" ? ' rel="' . $favicon["rel"] . '"':'')
			. (isset($favicon["href"]) && $favicon["href"]!="" ? ' href="' . $favicon["href"] . '"':'')
			. (isset($favicon["type"]) && $favicon["type"]!="" ? ' type="' . $favicon["type"] . '"':'')
			. " />\n";
	}
} ?>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->getCharset() ?>" />
<?php if ($this->has('meta-description')): ?>
		<meta name="description" content="<?php $this->output('meta-description') ?>" />
<?php endif; ?>
<?php if ($this->has('meta-keywords')): ?>
		<meta name="keywords" content="<?php $this->output('meta-keywords') ?>" />
<?php endif; ?>
<?php if ($this->has('meta-robots')): ?>
		<meta name="robots" content="<?php $this->output('meta-robots') ?>" />
<?php endif; ?>
<?php if ($this->has('meta-google-webmaster-tools')): ?>
		<meta name="verify-v1" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
		<meta name="google-site-verification" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
<?php endif; ?>
		<?php
		if ($checkRedirectToMobile === 'true') : ?>
			<script type="text/javascript">
				(function() {
					try {
						var website = <?php echo json_encode($mobileWebsite)?>;
						if (screen.width < parseInt(website.width))
						{
							var ua = window.navigator.userAgent;
							var msie = ua.indexOf("MSIE ");
							if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))
								document.execCommand("Stop");
							else
								window.stop();
							document.location = website.location;
						}
					}
					catch(e) {}
				})();
			</script>
<?php endif; ?>
		<link href="<?php echo $this->assets->getBasePath(); ?>assets/css/reset.css" rel="stylesheet" type="text/css"  />
		<style>
		<!--
		body,html {
			/*min-height:<?php echo max($content->website->height, (isset($page->height) ? $page->height : 0) ) ?>px;*/
			height:100%;
		}
		#mjs-site-preloader, body,html {
			<?php echo $style->getBackgroundColor('url(' . $basePath . $websitePreloader .') 50% 50% no-repeat');?>
		}
		
		#mjs-preloader {
			background: url('<?php echo $basePath . $contentPreloader?>') 50% 50% no-repeat ;
		}
		-->
		</style>

<?php 
	$this->stylesheets->add('assets/css/style.css?' . CONTROL_PANEL_VERSION);
	$this->javascripts->add('assets/jquery/jquery.min.js?' . CONTROL_PANEL_VERSION);
	$this->javascripts->add('assets/jquery/jquery.plugin.min.js?' . CONTROL_PANEL_VERSION);
	$this->stylesheets->add('assets/css/colorbox/colorbox.css?' . CONTROL_PANEL_VERSION);
	$this->javascripts->add('assets/jquery/colorbox/jquery.colorbox.js?' . CONTROL_PANEL_VERSION);
	$this->javascripts->add('assets/js/html5.js');
	$this->javascripts->add('assets/js/engine.min.js?' . CONTROL_PANEL_VERSION);
	/* HOOK_INJECTOR:DEMO_WEBSITE_LAYOUT_MIDDLE */
?>
<?php
/*
 * load requirements from structure.xml
 */
	$this->htmlHelpRender->initJavascripts($this);
	$this->htmlHelpRender->initStylesheets($this);
?>

<?php echo $this->stylesheets ?>
<?php echo $this->javascripts ?>
		<style>
		<!--
		#mjs-background-prev, #mjs-background-next {
			<?php $this->htmlHelpRender->getStyles($content, 'backgroundGalleryControlsArea', true); ?>
		}
		#mjs-background-prev:hover, #mjs-background-next:hover {
			<?php $this->htmlHelpRender->getStyles($content, 'backgroundGalleryControlsAreaHover', true); ?>
		}
		#mjs-background-next {
			<?php
				$img = $this->htmlHelpRender->getImg($content, 'backgroundGalleryImageNext', $basePath, true);
				if ($img['width'] == 0) 
				{
					$img = $this->htmlHelpRender->getImg($content, 'backgroundGalleryImageNextHover', $basePath);
					if ($img['width'] > 0) 
					{
						echo 'min-width: ' . $img['width'] . 'px;';
					}
				}
			?>
		}
		#mjs-background-next:hover {
			<?php $this->htmlHelpRender->getImg($content, 'backgroundGalleryImageNextHover', $basePath, true); ?>
		}
		#mjs-background-prev {
			<?php
				$img = $this->htmlHelpRender->getImg($content, 'backgroundGalleryImagePrev', $basePath, true);
				if ($img['width'] == 0) 
				{
					$img = $this->htmlHelpRender->getImg($content, 'backgroundGalleryImagePrevHover', $basePath);
					if ($img['width'] > 0) 
					{
						echo 'min-width: ' . $img['width'] . 'px;';
					}
				}
			?>
		}
		#mjs-background-prev:hover {
			<?php $this->htmlHelpRender->getImg($content, 'backgroundGalleryImagePrevHover', $basePath, true); ?>
		}
		#colorbox.system {
		    <?php $this->htmlHelpRender->getStyles($content, 'lightboxBorderStyle', $basePath, true); ?>
			}
		#colorbox.system #cboxPrevious, #colorbox.system #cboxNext {
			<?php $this->htmlHelpRender->getStyles($content, 'lightboxControlsArea', $basePath, true); ?>
			}
		#colorbox.system #cboxPrevious:hover, #colorbox.system #cboxNext:hover {
			<?php $this->htmlHelpRender->getStyles($content, 'lightboxControlsAreaHover', $basePath, true); ?>
			}
		#colorbox.system #cboxNext {
			<?php
			$img = $this->htmlHelpRender->getImg($content, 'lightboxNextButtonImage', $basePath, true);
			if ($img['width'] == 0)
			{
				$img = $this->htmlHelpRender->getImg($content, 'lightboxNextButtonImageHover', $basePath);
				if ($img['width'] > 0)
				{
					echo 'min-width: ' . $img['width'] . 'px;';
				}
			}
			?>
			}
		#colorbox.system #cboxNext:hover {
			<?php $this->htmlHelpRender->getImg($content, 'lightboxNextButtonImageHover', $basePath, true); ?>
			}
		#colorbox.system #cboxPrevious {
			<?php
			$img = $this->htmlHelpRender->getImg($content, 'lightboxPrevButtonImage', $basePath, true);
			if ($img['width'] == 0)
			{
				$img = $this->htmlHelpRender->getImg($content, 'lightboxPrevButtonImageHover', $basePath);
				if ($img['width'] > 0)
				{
					echo 'min-width: ' . $img['width'] . 'px;';
				}
			}
			?>
			}
		#colorbox.system #cboxPrevious:hover {
			<?php $this->htmlHelpRender->getImg($content, 'lightboxPrevButtonImageHover', $basePath, true); ?>
			}
		#cboxOverlay {
			background-color: <?php echo $this->htmlHelpRender->getColor($content, 'lightboxBackgroundColor')?>;
			}
		#mjs-browser {
			min-width: <?php echo $content->website->width?>px;
			<?php
				echo $style->getBackgroundStyle();
			?>
		}
		#mjs-main {
			width: <?php echo $content->website->width?>px;
			min-height:<?php echo max($content->website->height, (isset($page->height) ? $page->height : 0) ) ?>px;
		}
		-->
		</style>
<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
		<a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a>
	</div>
<![endif]-->
<?php
	$hookfilename = dirname(__FILE__) . '/_hook_head.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>

	<style id="mjs-styler-page"></style>
	<style id="mjs-styler-layout"></style>
	<style id="mjs-styler-website"></style>
    </head>
<body>
<?php
	$hookfilename = dirname(__FILE__) . '/_hook_body_top.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>
    <div id="mjs-site-preloader"><?php
		if (isset($javascriptDisabledMessage))
			echo '<noscript>' . $javascriptDisabledMessage . '</noscript>';
	?></div>
    <div id="mjs-preloader"></div>
    <div id="mjs-bg-preloader"></div>
	<div id="mjs-htmlContainer"></div>
	<div id="mjs-background">
		<div id="mjs-background-image1"></div>
		<div id="mjs-background-image2"></div>
	</div>
	
	<div id="mjs-bg-popup"></div>

	<div id="mjs-browser" style="">
		<!-- popup overlay -->
		<div id="mjs-main" class="mjs-holder" style="">

			<div id="mjs-website-bgContainer"></div>
			<div id="mjs-website">
				<?php echo $this->htmlHelpRender->dispatch($content, 'page'); ?>
				<?php //echo $this->htmlHelpRender->holders(); ?>
			</div>
			<div class="mjs-clear"></div>
			<div id="mjs-website-topContainer"></div>
			
			<a id="mjs-background-prev" href="#"></a>
			<a id="mjs-background-next" href="#"></a>

			<div id="mjs-popup-background"></div>
			<div id="mjs-popups-container">
				<div id="mjs-popup-1" class="mjs-popup">
					<?php echo $this->htmlHelpRender->dispatch($content, 'popup'); ?>
				</div>
			</div>
		</div>
			<div id="mjs-loginBox">
				<?php
					if ($websiteProtectionEnabled == "true")
					{
						echo $this->htmlHelpRender->renderLoginForm($content->website->loginForm);
					}
				?>
			</div>
	</div>
	<div id="mjs-animationContainer"></div>
	<div id="mjs-topContainer"></div>
	
<script type="text/javascript">
var _debug = {};
var response = {};
$(document).ready(function () {
	$('body, html').css('background', 'none');
	<?php if ($this->htmlHelpRender->get('page')) : ?>
		$('.mjs-layoutType-<?php echo $this->htmlHelpRender->get('page')->layoutTypeId?>, .mjs-pageType-<?php echo $this->htmlHelpRender->get('page')->pageTypeId?>').show();
	<?php endif; ?>
	response = <?php echo json_encode($this->htmlHelpRender->getResponse());?>;
	MotoJS.init(response);
});
</script>

<?php echo $this->htmlHelpRender->loadDeferJavascripts();?>

<?php 
	if ($this->has('googleAnalytics'))
		$this->output('googleAnalytics');
		
	$hookfilename = dirname(__FILE__) . '/_hook_body_bottom.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>
<?php if ($isFaceBook) { ?>
<div id="fb-root"></div>
<script type="text/javascript">
	// MotoJS.FB({'isFbTemplate' : true});
	MotoJS.FB.previewMode = <?php echo $iframePreview*1?>;
	MotoJS.FB.activated = (window.location != window.parent.location);
	window.fbAsyncInit = function() {

		if (document.location.protocol == 'https:') {
			FB._https = true;
		}
		MotoJS.FB.init('<?php echo $app_id?>');

	};

	// Load the SDK Asynchronously
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	}(document));
</script>
<?php } ?>
<?php
/* HOOK_INJECTOR:DEMO_WEBSITE_LAYOUT_END */
?>
</body>
</html>
