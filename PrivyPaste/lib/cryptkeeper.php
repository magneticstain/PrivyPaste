<?php
	namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  cryptkeeper.php - class for all encryption-related functionality
     */


	class CryptKeeper
	{
		// FUNCTIONS
		public static function getPkiKeyFromFile($keyType, $keyFile)
		{
			/*
			 *  Params:
			 *      - $keyType
			 *          - type of certificate being read in
			 *          - can be set to 'private' or 'public'
			 *
			 *  Usage:
			 *      - returns key in OpenSSL key resource form
			 *
			 *  Returns:
			 *      - key resource (later used with other encryption/openssl functions)
			 *      - FALSE if not able to open or read
			 */

			// try to open key file and return to user
			// format $keyFile to filename format that openssl_pkey_get_X() requires to read in from file
			$keyFile = 'file://'.$keyFile;

			// function used to read in file is dependent on key type

			// normalize $keyType
			$keyType = strtolower($keyType);

			$key = '';
			if($keyType === 'public')
			{
				// read in public key
				$key = openssl_pkey_get_public($keyFile);
			}
			elseif($keyType === 'private')
			{
				// read in private key
				$key = openssl_pkey_get_private($keyFile);
			}

			// see if file read was successful
			if($key)
			{
				return $key;
			}

			// return blank string if anything goes wrong
			return '';
		}

		public static function encryptString($publicKey, $plaintext, $isBase64Encoded = false)
		{
			/*
			 *  Params:
			 *      - $publicKey
			 *          - public key resource, normally read in from user-generated key file
			 *      - $plaintext
			 *          - plaintext that will need to be encrypted
			 *      - $isBase64Encoded
			 *          - bool to indicate whether data should be returned as raw binary data blob or as Base64 encoded string
			 *
			 *  Usage:
			 *      - encrypts the given plaintext using the provided public key
			 *
			 *  Returns:
			 *      - binary blob OR string
			 */

			$ciphertext = '';

			// try encrypting data (default padding option is used)
			// ciphertext is stored by supplying $ciphertext var as function param
			openssl_public_encrypt($plaintext, $ciphertext, $publicKey);

			// return ciphertext as binary blob or string depending on flag param
			if($isBase64Encoded === true)
			{
				return base64_encode($ciphertext);
			}

			// default is to return as a binary blob
			return $ciphertext;
		}

		public static function decryptString($privateKey, $ciphertext, $isBase64Encoded)
		{
			/*
			 *  Params:
			 *      - $privateKey
			 *          - private key resource, normally read in from user-generated key file
			 *      - $ciphertext
			 *          - previously-encrypted text that will need to be decrypted using the private key
			 *
			 *  Usage:
			 *      - decrypts the given ciphertext using the provided RSA private key
			 *
			 *  Returns:
			 *      - binary blob
			 */

			$plaintext = '';

			// check if data was sent in base64 encoding or a binary blob
			if($isBase64Encoded === true)
			{
				// decode
				$ciphertext = base64_decode($ciphertext);
			}

			// try decrypting data (default padding option is used)
			// plaintext is stored by supplying $plaintext var as function param
			openssl_private_decrypt($ciphertext, $plaintext, $privateKey);

			return $plaintext;
		}



		public static function generateUniquePasteID()
		{
			/*
			*  Params:
			*      - NONE
			*
			*  Usage:
			*      - generates an 8 character unique identifier that is correlated to a paste
			*      - string is generated from a 4-byte random bitstream. This allows for 256^4, or 4294967296, possible permutations
			*
			*  Returns:
			*      - string
			*/

			$UID = '';
			$numRandomBytes = 4;
			$cryptographicallySecureBitStream = true;

			// generate cryptographically-secure bitstream
			if($binaryString = openssl_random_pseudo_bytes($numRandomBytes, $cryptographicallySecureBitStream))
			{
				// convert bitstream to hex
				$UID = bin2hex($binaryString);
			}

			return $UID;
		}
	}
?>