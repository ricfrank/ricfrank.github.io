<!DOCTYPE html>
<html>
    <head>
		<title></title>
<?php
/**
 * @var $basePath string
 * @var $websitePreloader string
 * @var $contentPreloader string
 */
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
		<?php $this->stylesheets->add('assets/css/reset.css');?>
		<?php $this->javascripts->add('assets/jquery/jquery.min.js');?>
		<?php echo $this->stylesheets ?>		
		<?php echo $this->javascripts ?>
		<style>
		<!--
		html, body {
			height: 100%;
			min-height: 100% !important;
			background: #E7EBF2;
		}
		#preview {
			text-align: center;
			padding-top: 10px;
			padding-bottom: 10px;
		}
		#preview-iframe {
			width: <?php echo $websiteDimension['width']?>px;
			height: <?php echo $websiteDimension['height']?>px;
			margin:0 auto;
			background: #FFFFFF;
			padding: 20px;
			border-color: #C4CDE0;
			border-radius: 3px 3px 3px 3px;
			-o-border-radius: 3px 3px 3px 3px;
			-moz-border-radius: 3px 3px 3px 3px;
			-webkit-border-radius: 3px 3px 3px 3px;
			-ms-border-radius: 3px 3px 3px 3px;
			border-style: solid;
			border-width: 1px 1px 2px;
		}
		-->
		</style>
		<script type="text/javascript">
			function setFrameHeight(height) {
				$('#preview-iframe').height(height);
			}
			function setFrameWidth(width) {
				$('#preview-iframe').width(width);
			}
			function getFrame() {
				return $('#preview-iframe');
			}

            function getWindowSize(){
                if (window.innerWidth != undefined) {
                    return [window.innerWidth, window.innerHeight];
                } else {
                    var B = document.body,
                        D = document.documentElement;
                    return [Math.max(D.clientWidth, B.clientWidth),
                            Math.max(D.clientHeight, B.clientHeight)];
                }
            }

            function getScrollPosition(){
                if (window.scrollX != undefined) {
                    return [window.scrollX, window.scrollY];
                } else{
                    var B = document.body,
                        D = document.documentElement;
                    return [Math.max(D.scrollLeft, B.scrollLeft),
                            Math.max(D.scrollTop, B.scrollTop)];
                }
            }

            function getInnerWidth() {
                return getWindowSize()[0];
            }

            function getInnerHeight() {
                return getWindowSize()[1];
            }

            function getScrollX() {
                return getScrollPosition()[0];
            }

            function getScrollY() {
                return getScrollPosition()[1];
            }
		</script>
	</head>
	<body>
		<div id="preview">
			<iframe src="<?php echo $basePath?><?php echo ltrim($pageURL, '/');?>?fb_request=preview&preview=true" id="preview-iframe" name="preview-iframe"
				scrolling="no" style="overflow:hidden;margin:0px;"
				allowtransparency="true" width="<?php echo $websiteDimension['width']?>" height="100%" border="0" hspace="0" marginheight="0" marginwidth="0" vspace="0" frameBorder="0"></iframe>
		</div>
	</body>
</html>