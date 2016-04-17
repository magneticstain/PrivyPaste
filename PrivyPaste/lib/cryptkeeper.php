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
		// KEY GENERATION AND MANIPULATION
		public static function generateKey($keySize = 32, $useCryptographicallyStrongAlgorithm = true)
		{
			/*
			 *  Params:
			 *      - $keySize
			 *          - size of key to be generated (in bytes)
			 *          - default = 32, generating a 256-bit key
			 *
			 *      - $useCryptographicallyStrongAlgorithm
			 *          - sets option to use cryptographically-strong algorithm when generating a key
			 *          - default = true
			 *          - it is HIGHLY suggested that true always be set here except for testing
			 *
			 *  Usage:
			 *      - generate cryptographically-secure set of random bytes to be used as AES key
			 *
			 *  Returns:
			 *      - binary key
			 *      - false if key generation failed
			 */

			// uses the OpenSSL random numbers algorithm :: https://wiki.openssl.org/index.php/Random_Numbers
			// http://php.net/manual/en/function.openssl-random-pseudo-bytes.php
			$key = openssl_random_pseudo_bytes($keySize, $useCryptographicallyStrongAlgorithm);

			return $key;
		}

		public static function writeKeyToFile($key, $filename)
		{
			/*
			 *  Params:
			 *      - $key
			 *          - binary key data
			 *          - should be generated using generateKey()
			 *
			 *      - $filename
			 *          - filename where key data is stored
			 *
			 *  Usage:
			 *      - writes given binary key data to file
			 *
			 *  Returns:
			 *      - true if write suceeded, false if it fails
			 */

			// check if the key file exists, and if not, create it
			if(!file_exists($filename))
			{
				if(!@touch($filename))
				{
					error_log('PrivyPaste :: CryptKepper() -> writeKeyToFile() :: File Handle Error :: could not create key file :: [ '.$filename.' ]');

					return false;
				}
				else
				{
					// set file permissions
					chgrp($filename,'www-data');
					chmod($filename,0775);
				}
			}

			// write the key to the file
			try
			{
				// attempt to open file
				$fileHandle = @fopen($filename,'a+');

				if($fileHandle)
				{
					// write to file
					$fileWrite = fwrite($fileHandle, $key);

					// close file handle
					fclose($fileHandle);

					return $fileWrite;
				}
				else
				{
					error_log('PrivyPaste :: CryptKepper() -> writeKeyToFile() :: File Handle Error :: could not open key file :: [ '.$filename.' ]');
				}
			}
			catch(\Exception $e)
			{
				error_log('PrivyPaste :: CryptKepper() -> writeKeyToFile() :: File Handle Error :: [ '.$e->getMessage().' ]');
			}

			return false;
		}

		public static function readKeyFromFile($filename)
		{
			/*
			 *  Params:
			 *      - $filename
			 *          - filename where key data is stored
			 *
			 *  Usage:
			 *      - reads key data from file
			 *
			 *  Returns:
			 *      - data if read suceeded, false if it fails
			 */

			// generate file handle
			if($fileHandle = fopen($filename, 'r'))
			{
				// attempt to read from file
				return fread($fileHandle, filesize($filename));
			}
			else
			{
				return false;
			}
		}

		// PSUEDORANDOM DATA
		public static function generateInitializationVector($ivLength)
		{
			/*
			*  Params:
			*      - $ivLength
			*           - length of IV to be generated
			*
			*  Usage:
			*      - generates a 16-byte IV for use when encrypting (and later decrypting) data
			*
			*  Returns:
			*      - string
			*/

			// normalize to int to meet function param requirements
			$ivLength = (int)$ivLength;

			// generate IV
			// this var will be updated by openssl function if cryptographically secure function was successfully used
			$usedSecureAlgorithm = false;
			if($iv = openssl_random_pseudo_bytes($ivLength, $usedSecureAlgorithm))
			{
				// IV could be generated using OpenSSL functions
				if($usedSecureAlgorithm)
				{
					// IV was generated using a secure algorithm, return it
					return $iv;
				}
			}

			return '';
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

		// ENCRYPTION AND DECRYPTION
		public static function encryptString($encKey, $iv, $plaintext, $hmacKey)
		{
			/*
			 *  Params:
			 *      - $encKey
			 *          - encryption key resource (represented in binary)
			 *          - normally read in from user-generated key file
			 *          - used for encrypting plaintext
			 *
			 *      - $iv
			 *          - initialization vector for encryption process
			 *          - more info: https://en.wikipedia.org/wiki/Initialization_vector
			 *
			 *      - $plaintext
			 *          - plaintext that will need to be encrypted
			 *
			 *      - $hmacKey
			 *          - encryption key resource (represented in binary)
			 *          - normally read in from user-generated key file
			 *          - used for message authentication algo's
			 *
			 *  Usage:
			 *      - encrypts the given plaintext using the provided AES key
			 *
			 *  Returns:
			 *      - string
			 */

			// encryption text is using an encrypt-then-mac setup
			// try encrypting data
			$ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $encKey, 0, $iv);

			// check if encryption was successful
			if(!$ciphertext)
			{
				$errorMsg = 'PrivyPaste :: CryptKepper() -> encryptString() :: OpenSSL Error';

				// check if openssl returned an error
				while($openSSLErrorMsg = openssl_error_string())
				{
					$errorMsg .= ":: [ $openSSLErrorMsg ]";
				}

				error_log($errorMsg);

				return false;
			}
			else
			{
				// encryption was successful, take MAC of the ciphertext and return it all concatonated by a '.'
				$mac = hash_hmac('sha256', $ciphertext, $hmacKey, false);

				return $mac.'.'.$ciphertext;
			}
		}

		public static function decryptString($encKey, $iv, $ciphertext, $hmacKey)
		{
			/*
			 *  Params:
			 *      - $encKey
			 *          - encryption key resource (represented in hex)
			 *          - normally read in from user-generated key file
			 *          - used for encrypting plaintext
			 *
			 *      - $iv
			 *          - initialization vector for encryption process
			 *          - more info: https://en.wikipedia.org/wiki/Initialization_vector
			 *
			 *      - $ciphertext
			 *          - previously-encrypted text that will need to be decrypted using the key
			 *
			 *      - $hmacKey
			 *          - encryption key resource (represented in hex)
			 *          - normally read in from user-generated key file
			 *          - used for message authentication algo's
			 *
			 *  Usage:
			 *      - decrypts the given ciphertext using the provided AES key
			 *
			 *  Returns:
			 *      - string
			 */

			$errorMsg = '';

			// split hmac and ciphertext
			$encryptedData = explode('.', $ciphertext);
			$hmac = $encryptedData[0];
			$ciphertext = $encryptedData[1];

			// authenticate ciphertext integrity using mac
			$calculatedHMAC = hash_hmac('sha256', $ciphertext, $hmacKey, false);
			if($calculatedHMAC !== $hmac)
			{
				error_log('PrivyPaste :: CryptKepper() -> decryptString() :: HMAC Error :: message failed HMAC authentication');

				return false;
			}

			// try decrypting data
			if(!$plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $encKey, 0, $iv))
			{
				$errorMsg = 'PrivyPaste :: CryptKepper() -> decryptString() :: OpenSSL Error';

				# check if openssl returned an error
				while($openSSLErrorMsg = openssl_error_string())
				{
					$errorMsg .= " :: [ $openSSLErrorMsg ]";
				}
			}

			// check if error message needs to be logged
			if($errorMsg !== '')
			{
				error_log($errorMsg);
			}

			return $plaintext;
		}
	}
?>