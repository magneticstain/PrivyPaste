<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/current_version/acct/update/token - api feature to update the user token in the database
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
		// token set, action is to update it in the db
		// * check if it already exists
		// * if not, insert into db
		// TODO: Add token-check logic
//		$pasteBin->
	}

	// output in json format
	echo json_encode($jsonOutput);
?>