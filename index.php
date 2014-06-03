<?php
/**
 *  Josh Carlson
 *  Created: 5/24/14 10:42 PM
 *  Email: jcarlson@carlso.net
 */

	// include base config files
	require 'conf/base.php';

	// include autoloader logic
	require 'lib/autoloader.php';

	// include prereqs
	require 'lib/prereq.php';

	// output paste form
	echo $pasteBin->getToken();
	echo $pasteBin;
?>