#!/usr/bin/php

<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  generate_key.php - PHP script for generating a random key for AES encryption. Normally ran during initial install via installation script (./install.sh)
	 */

	//
	// CONFIGS AND LIBRARIES
	//

	// configuration files
	// global
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/conf/global.php';
	// pki
	require_once BASE_DIR.__NAMESPACE__.'/conf/pki.php';

	// classes
	// autoloader
	require_once BASE_DIR.__NAMESPACE__.'/lib/autoloader.php';

	// generate key using default options
	$key = \privypaste\CryptKeeper::generateKey();

	// write key to file
	\privypaste\CryptKeeper::writeKeyToFile($key,KEY_FILE);

	exit(0)
?>
