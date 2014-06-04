<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/current_version/acct/token/check - api feature to verify a given user token in the database
	 */

	$directoryRoot = '../../../../../';

	// include base config file
	require $directoryRoot.'conf/base.php';

	// include autoloader logic
	require BASE_DIR.'lib/autoloader.php';

	// include prereqs
	require BASE_DIR.'lib/prereq.php';

	$jsonOutput = array(
		'error' => 'something went wrong!'
	);

	// check for token get variable
	if(isset($_GET['token']) && $pasteBin->isValidString($_GET['token']))
	{
		// token set, action is to look it up it in the db
		$jsonOutput = $pasteBin->checkTokenInDB($_GET['token']);
	}

	// output in json format
	echo json_encode($jsonOutput);
?>