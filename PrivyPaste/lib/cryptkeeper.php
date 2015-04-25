<?php
    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  cryptkeeper.php - class for all encryption-related functionality
     */

namespace privypaste;


class CryptKeeper
{
	public $plaintext = '';
	private $key = '';

	public function __construct($plaintext, $key)
	{
		// set variables, verification happens alter upstream during 'en|de'cryption
		$this->plaintext = $plaintext;
		$this->key = $key;
	}

	// ACTIONS
	public static function encryptString()
	{

	}

	public static function decryptString()
	{

	}
}