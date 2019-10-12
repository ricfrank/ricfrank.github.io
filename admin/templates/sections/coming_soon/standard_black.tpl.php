<?php
/*<!--
ThemeAuthor: FM
ThemeName: Standard Black
ThemeDescription: Cup of coffee.
-->*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $websiteTitlePrefix; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (isset($pageFavicon)): ?>
	<?php
	foreach($pageFavicon as $favicon)
	{
		echo '<link '
			. (isset($favicon["rel"]) && $favicon["rel"]!="" ? ' rel="' . $favicon["rel"] . '"':'')
			. (isset($favicon["href"]) && $favicon["href"]!="" ? ' href="' . $favicon["href"] . '"':'')
			. (isset($favicon["type"]) && $favicon["type"]!="" ? ' type="' . $favicon["type"] . '"':'')
			. " />\n";
	}
	?>
<?php endif;?>

<meta http-equiv="Content-Style-Type" content="text/css" />
<style type="text/css">
	* {
		margin:0;
		padding:0;
	}
	html, body {
		height:100%;
	}
	html {
		min-width:512px;
	}
	body {
		background:url(images/sections/coming_soon/<?php echo $underConstructionTheme;?>_bg.jpg) no-repeat center top #0f0f0f;
	}

	img {
		border:0;
		vertical-align:top;
		text-align:left;
	}

	#main {
		margin:0 auto;
	}
		#main .indent {
			padding:420px 0 0 0;
		}
			#main img {
				display:block;
				margin:0 auto;
			}
			#main p {
				margin:0 auto;
				width: 512px;
			}
			#main a {
				text-decoration: none;
			}
			#main a:hover {
				text-decoration: underline;
			}
</style>
</head>

<body>
  <div id="main">
    <div class="indent">
    	<img alt="" src="images/sections/coming_soon/<?php echo $underConstructionTheme;?>_coming.gif" />
      <p><?php echo $underConstructionMessage; ?></p>
    </div>
  </div>
</body>
</html>