<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $account ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

$(document).ready(function () {
	try {
	MotoJS.events.subscribe('onSwitchFinish', function() {
		try {
			_gaq.push(['_trackPageview', MotoJS.website.basePath + MotoJS.page.getUrl()]);
		}catch(e){}
	});
	}catch(e){}
	
});
</script>
