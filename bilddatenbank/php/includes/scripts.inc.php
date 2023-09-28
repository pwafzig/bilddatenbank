<?php include(DOCROOT.INSTALLPATH."/scripts/window.js"); ?>
	<script type="text/javascript" language="javascript">
	    var http_request = false;
	    function macheRequest(url) {
	        http_request = false;
	        if (window.XMLHttpRequest) { // Mozilla, Safari,...
	            http_request = new XMLHttpRequest();
	        } else if (window.ActiveXObject) { // IE
	            try {
	                http_request = new ActiveXObject("Msxml2.XMLHTTP");
	            } catch (e) {
	                try {
	                    http_request = new ActiveXObject("Microsoft.XMLHTTP");
	                } catch (e) {}
	            }
	        }
	        if (!http_request) {
	            alert('Ende :( Kann keine XMLHTTP-Instanz erzeugen');
	            return false;
	        }
	        http_request.onreadystatechange = alertInhalt;
	        http_request.open('GET', url, true);
	        http_request.send(null);
	    }
	</script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" language="javascript"></script>
	<script type="text/javascript" src="/<?php echo INSTALLPATH; ?>/lib/thickbox/thickbox-compressed.js" language="javascript"></script>
</head>
