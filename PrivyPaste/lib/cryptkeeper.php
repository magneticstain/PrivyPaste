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
//	public $plaintext = '';
//	private $key = '';
//
//	public function __construct($plaintext, $key)
//	{
//		// set variables, verification happens alter upstream during 'en|de'cryption
//		$this->plaintext = $plaintext;
//		$this->key = $key;
//	}

	// FUNCTIONS
	public static function getPublicKey($keyFile)
	{
		/*
		 *  Params:
		 *      - NONE
		 *
		 *  Usage:
		 *      - returns public key in OpenSSL key resource form
		 *
		 *  Returns:
		 *      - key resource (later used with other encryption/openssl functions)
		 *      - FALSE if not able to open or read
		 */

		// try to open public key file and return to user
		if($publicKey = openssl_pkey_get_public($keyFile))
		{
			echo '[DEBUG] Read in public key from file...';

			$publicKeyRaw = '';
			openssl_pkey_export($publicKey, $publicKeyRaw);
			echo "[DEBUG] PUBKEY_TEXT: ".$publicKeyRaw."\n";

		}

		return $publicKey;
	}

	public static function getPrivateKey($keyFile)
	{
		/*
		 *  Params:
		 *      - NONE
		 *
		 *  Usage:
		 *      - returns private key in XXXXX form
		 *
		 *  Returns:
		 *      - key resource (later used with other encryption/openssl functions)
		 *      - FALSE if not able to open or read
		 */

		// try to open private key file and return to user
		return openssl_pkey_get_private($keyFile);
	}

	public static function encryptString($publicKey, $plaintext)
	{
		/*
		 *  Params:
		 *      - $publicKey - public key resource, normally read in from user-generated key file
		 *      - $plaintext - plaintext that will need to be encrypted
		 *
		 *  Usage:
		 *      - encrypts the given plaintext using the provided public key
		 *
		 *  Returns:
		 *      - string
		 */

		$ciphertext = '';

		// try encrypting data (default padding option is used)
		// ciphertext is stored by supplying $ciphertext var as function param
		openssl_public_encrypt($plaintext, $ciphertext, $publicKey);

		return $ciphertext;
	}

	public static function decryptString($privateKey, $ciphertext)
	{
		/*
		 *  Params:
		 *      - $privateKey - private key resource, normally read in from user-generated key file
		 *      - $ciphertext - previously-encrypted text that will need to be decrypted using the private key
		 *
		 *  Usage:
		 *      - decrypts the given ciphertext using the provided RSA private key
		 *
		 *  Returns:
		 *      - string
		 */

		$plaintext = '';

		// try decrypting data (default padding option is used)
		// plaintext is stored by supplying $plaintext var as function param
		openssl_public_encrypt($ciphertext, $plaintext, $privateKey);

		return $plaintext;
	}
}