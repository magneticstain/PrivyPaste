<?php

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  conf/pki.php - sets variables used for cryptographic purposes
     */

	// KEY FILES
	define('ENC_KEY_FILE', '/var/www/html/PrivyPaste/pki/encryption_key.bin');
	define('HMAC_KEY_FILE', '/var/www/html/PrivyPaste/pki/hmac_key.bin');
?>