<?php
/**
 *  Josh Carlson
 *  Created: 5/9/14 7:39 PM
 *  Email: jcarlson@carlso.net
 */

/*
 *  lib/Paste.php - a library containing the class Paste() - an object representing a paste and all associated
 * 					characteristics. One of the components of the 'model' portion of the underlying MVC
 * 					framework.
 */

class Paste extends Pastebin
{
	private $pasteId;
	private $ownerId;
	private $created;
	public $contents;

	// CONSTRUCTOR
	public function __construct(
		$db_conn,
		$pasteId = 1,
		$ownerId = 1,
		$created = null,
		$contents = ''
	)
	{
		if(
			!$this->setDbConn($db_conn)
			|| !$this->setPasteId($pasteId)
			|| !$this->setOwnerId($ownerId)
			|| !$this->setCreatedTime($created)
			|| !$this->setContents($contents)
		)
		{
			throw new Exception('ERROR: Paste() -> could not create new paste!');
		}
	}

	// GETTERS
	public function getPasteId()
	{
		return $this->pasteId;
	}

	public function getOwnerId()
	{
		return $this->ownerId;
	}

	public function getCreatedTime()
	{
		return $this->created;
	}

	public function getContents()
	{
		return $this->contents;
	}

	// SETTERS
	public function setPasteId($pasteId)
	{
		// normalize to int
		$pasteId = (int)$pasteId;

		// paste ID should be between 1 and 9999999999
		if(0 <= $pasteId && $pasteId <= 9999999999)
		{
			$this->pasteId = $pasteId;

			return true;
		}

		return false;
	}

	public function setOwnerId($ownerId)
	{
		// normalize to int
		$ownerId = (int)$ownerId;

		// owner ID should be between 1 and 9999999999
		if(0 <= $ownerId && $ownerId <= 9999999999)
		{
			$this->ownerId = $ownerId;

			return true;
		}

		return false;
	}

	public function setCreatedTime($createdTime)
	{
		// created time should be a valid ISO 8601 timestamp (e.g. YYYY-MM-DDTHH:MM:SS)
		if(strtotime($createdTime))
		{
			// valid timestamp
			$this->created = $createdTime;
		}
		else
		{
			// invalid timestamp
			// set to default - current time
			$this->created = date('Y-m-d H-i-s');
		}

		return true;
	}

	public function setContents($contents, $encrypt = true)
	{
		// contents can be encrypted here (default), or the developer can specify that the content is already
		// encrypted
		if($encrypt)
		{
			// encrypt contents
			if($contents = $this->encryptString($contents))
			{
				// verify data
				// should always be a base64 string after encrypting
				// see this stackoverflow thread for source of this base64 verification
				// https://stackoverflow.com/questions/4278106/how-to-check-if-a-string-is-base64-valid-in-php
				if(base64_encode(base64_decode($contents)) === $contents)
				{
					// valid
					$this->contents = $contents;

					return true;
				}
			}
		}
		elseif(Pastebin::isValidString($contents) || empty($contents))
		{
			// valid
			$this->contents = $contents;

			return true;
		}

		// all else fails...
		return false;
	}

	// OTHER FUNCTIONS
	// ENCRYPTION
	private function encryptString($string)
	{
		// encrypts given data using the public key specified in the base.php config file
		$encryptedString = '';

		// extract public key
		$publicKey = file_get_contents(PUBLIC_KEY);

		// check for keys
		if($this->isValidString($publicKey))
		{
			// try encrypting
			if(openssl_public_encrypt($string, $encryptedString, $publicKey))
			{
				// base64 encode it and return
				return base64_encode($encryptedString);
			}
		}
		else
		{
			error_log('ERROR: Paste() -> Public key not defined!');
		}

		return false;
	}

	private function decryptString($encryptedString)
	{
		// decrypts given encrypted string using the private key specified in the base.php conf file
		$decryptedString = '';

		// extract private key
		$privateKey = file_get_contents(PRIVATE_KEY);

		// check for public key
		if($this->isValidString($privateKey))
		{
			// try to decrypt
			if(openssl_private_decrypt(base64_decode($encryptedString), $decryptedString, $privateKey))
			{
				return $encryptedString;
			}
		}
		else
		{
			error_log('ERROR: Paste() -> Private key not defined!');
		}

		return false;
	}

	// DB
	public function sendPasteToDB()
	{
		// creates a new paste record in the db
	}

	public function updatePasteInDB()
	{
		// selects record with set paste ID and updates the record with the object data
	}
}