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
	$createPasteObj = true;

	// include base config file
	require $directoryRoot.'conf/base.php';

	// include autoloader logic
	require BASE_DIR.'lib/autoloader.php';

	// include prereqs
	require BASE_DIR.'lib/prereq.php';

	// FUNCTIONS
	function getIncomingData()
	{
		// check for supplied data
		// can be sent via POST or GET
		$pasteData = null;

		// check for post
		if(isset($_POST['c']) || isset($_POST['content']))
		{
			// get data from post
			echo "POST method used";
		}
		elseif(isset($_GET['c']) || isset($_GET['content']))
		{
			// get data via GET
			echo "GET method used";
		}

		return $pasteData;
	}

	// start paste generation
	$jsonOutput = array(
		'error' => 'something went wrong!'
	);

	// grab incoming data
	$pasteData = getIncomingData($paste);

	// try adding to db
//	if($paste->sendPasteToDB())
//	{
//		// paste has been added
//		// echo out paste ID
//		$jsonOutput = '';
//	}

	// debug
	echo '<pre>';
	var_dump($paste);
	echo '</pre>';

	// output in json format
	echo json_encode($jsonOutput);
?>