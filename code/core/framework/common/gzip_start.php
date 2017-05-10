<?php
		$phpver = phpversion();
		$useragent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
		if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) ) {
			if ( extension_loaded('zlib') ) {
				//ob_end_clean();
				ob_start('ob_gzhandler');
			}
		} else if ( $phpver > '4.0' ) {
			if ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') ) {
				if ( extension_loaded('zlib') ) {
					$do_gzip_compress = true;
					ob_start();
					ob_implicit_flush(0);
					header('Content-Encoding: gzip');
				}
			}
		} 
?>