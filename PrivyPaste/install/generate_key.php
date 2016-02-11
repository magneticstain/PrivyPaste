<?php
	namespace privypaste;

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
	require_once '../conf/global.php';
	// pki
	require_once '../conf/pki.php';

	// classes
	require_once '../lib/cryptkeeper.php';

	/*
	 * Crypto Specs:
	 *  Encryption:
	 *      CIPHER: AES256
	 *      MODE: CBC
	 *  HMAC
	 *      HASH FUNCTION: sha256
	 */

	// generate encryption key using default options
	$encKey = \privypaste\CryptKeeper::generateKey();

	// generate HMAC key
	$hmacKey = \privypaste\CryptKeeper::generateKey();

	// write keys to file
	\privypaste\CryptKeeper::writeKeyToFile($encKey, ENC_KEY_FILE);
	\privypaste\CryptKeeper::writeKeyToFile($hmacKey, HMAC_KEY_FILE);

	exit(0)
?>
