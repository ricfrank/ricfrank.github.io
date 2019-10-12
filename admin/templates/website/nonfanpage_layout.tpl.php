<!DOCTYPE html>
<html>
  <head>
    <title><?php $this->output('title') ?></title>
<?php if (isset($pageFavicon))
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

    <meta name="robots" content="noindex, nofollow" />

    <?php if ($this->has('meta-google-webmaster-tools')): ?>
    <meta name="verify-v1" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
    <meta name="google-site-verification" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
    <?php endif; ?>

	<?php $this->javascripts->add('assets/jquery/jquery.min.js'); ?>
    <?php echo $this->javascripts ?>
    <?php echo $this->stylesheets ?>

<style type="text/css">
<!--
body,html,img {margin:0;padding:0;border:0;overflow-x: hidden;}
html { height:100%; }
-->
</style>

  </head>
  <body>

	<div id="fb-root"></div>
	<script type="text/javascript">
	window.fbAsyncInit = function() {
		if (document.location.protocol == 'https:') {
			FB._https = true;
		}
		FB.init({appId: '<?php echo $app_id?>', status: true, cookie: true, xfbml: true});
	};
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		ref.parentNode.insertBefore(js, ref);
	}(document));
	</script>

<?php

	$content = trim($this->get('content'));
	$tmplfile = dirname(__FILE__) . '/design.tpl.php';
	if ((file_exists($tmplfile)) && (filesize($tmplfile) > 0))
	{
		$content = str_replace('%TEMPLATE_CONTENT%', $content, implode('', file($tmplfile)));
	}
	echo $content;
?>

    <?php if ($this->has('googleAnalytics')): ?>
    <?php $this->output('googleAnalytics') ?>
    <?php endif; ?>
  </body>
</html>
