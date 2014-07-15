<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/current_version/paste/add - api feature to insert a new paste into the database
	 */

	$directoryRoot = '../../../../';

	// we will need a Paste() object
	$createPaste = true;

	// include base config file
	require $directoryRoot.'conf/base.php';

	// include autoloader logic
	require BASE_DIR.'lib/autoloader.php';

	// include prereqs
	require BASE_DIR.'lib/prereq.php';

	// start paste generation
	$jsonOutput = array(
		'error' => 'something went wrong!'
	);

	// check for params
//	echo '<pre>';
//	var_dump($paste);
//	echo '</pre>';

	// output in json format
	echo json_encode($jsonOutput);
?>