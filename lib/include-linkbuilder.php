<?php

	$slashes = preg_match_all('/\//',isset($_SERVER['REDIRECT_URL'])? $_SERVER['REDIRECT_URL']:$_SERVER['REQUEST_URI']);
	$slashes -= 2;
	$x_link_prefix = $link_prefix = ''; #x_link_prefix: external link prefix
	for ($i=0;$i<$slashes;$i++) {
		$x_link_prefix .= '../';
		if ($i > 0) $link_prefix .= '../';
	} $link_prefix = ($link_prefix == '')? './':$link_prefix;
	$x_link_prefix = ($x_link_prefix == '')? './':$x_link_prefix;
	# !-Compute relative linking prefix

?>
