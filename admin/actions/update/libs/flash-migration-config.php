<?php
 define("DEFAULT_CENTER_LAT", 40.7143528); define("DEFAULT_CENTER_LNG", -74.0059731); define("HTML_TEMPLATE_TYPE", 1); define("FLASH_TEMPLATE_TYPE", 2); define("JS_MAPS_INCLUDES", '<script src="http://maps.google.com/maps/api/js?v=3&sensor=false" type="text/javascript"></script>'); define('MAPS_HTML_WIDGET_CONTENT', '<?xml version="1.0" encoding="utf-8"?><htmlwidget><configuration><option id="showScrollBars"><value><![CDATA[false]]></value></option><option id="useIFrame"><value><![CDATA[false]]></value></option></configuration>
	<data><![CDATA[#HTML_CONTENT#]]></data>
</htmlwidget>'); define('MAPS_RICH_HTML_CONTENT', '<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/ms?msid=204293175747830583907.0005022420ef3bca6a816&msa=0&ie=UTF8&t=m&ll=40.79042,-73.945541&spn=0.462677,1.056747&output=embed"></iframe><br /><small>View <a href="https://www.google.com/maps/ms?msid=204293175747830583907.0005022420ef3bca6a816&msa=0&ie=UTF8&t=m&ll=40.79042,-73.945541&spn=0.462677,1.056747&source=embed" style="color:#0000FF;text-align:left">Google map</a> in a larger map</small>'); define('MAPS_HTML_CONTENT2', '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
<div id="map#MAP_ID#" style="width:#WIDTH#px; height:#HEIGHT#px;"></div>
    <script type="text/javascript">
         function loadScript(src) {

            var script = document.createElement("script");
            script.type = "text/javascript";
            document.getElementsByTagName("head")[0].appendChild(script);
            script.src = src;
        }

        if (#GET_API_SCRIPT# && ((typeof google) === "undefined")) {
            loadScript(\'http://maps.googleapis.com/maps/api/js?v=3&sensor=false&callback=initialize\');
        } else {
            initialize();
        }

        function initialize() {
            setTimeout(function() {
                var locations = #LOCATIONS#;

                var map = new google.maps.Map(document.getElementById(\'map#MAP_ID#\'), {
                    zoom: 10,
                    center: new google.maps.LatLng(#CENTER_LAT#, #CENTER_LNG#),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var marker, i;
                for (i = 0; i < locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                        map: map
                    });
                }
            }, 100);

        }
    </script>

</body>
</html>');