<?php
/**
 *  Josh Carlson
 *  Created: 5/9/14 7:39 PM
 *  Email: jcarlson@carlso.net
 */

/*
 *  lib/Pastebien.php - a library containing the class Pastebin() - an all-encompassing object with site-wide functions
 * 						that other classes can inherit from. One of the components of the 'model' portion of the underlying MVC
 * 						framework.
 */

class Pastebin
{
	protected $token;
	protected $userId;
	protected $username;

	public function __construct(
		$userId = 0,
		$username = 'default',
		$token = null
	)
	{
		if(
			!$this->setUserId($userId)
			|| !$this->setUsername($username)
			|| !$this->setToken($token)
		)
		{
//			echo $this->setUserId($userId)." -- ".$this->setUsername($username)." -- ".$this->setToken($token);
			// invalid; throw exception
			throw new Exception('ERROR: could not create new pastebin!');
		}
	}

	// GETTERS
	public function getUserId()
	{
		return $this->userId;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getToken()
	{
		return $this->token;
	}

	// SETTERS
	public function setUserId($userId)
	{
		// user id must be a numeral up to 11 digits long (due to db constraints)
		if(is_numeric($userId) && ($userId >= 0 && $userId < 100000000000))
		{
			// valid
			$this->userId = $userId;

			return true;
		}

		// invalid
		return false;
	}

	public function setUsername($username)
	{
		// the username can be any string less than 100 characters

		// trim incoming stream
		$username = trim($username);

		if($this->isValidString($username))
		{
			// valid
			$this->username = $username;

			return true;
		}

		// invalid
		return false;
	}

	public function setToken($token)
	{
		// token is a 16 byte (128-bit) hex string of random bytes generated from OpenSSL
		$newToken = '';

		// check input token
		if($token === null)
		{
			// try to generate new token
			if(!$newToken = $this->generateCSRFToken())
			{
				// log error
				error_log('ERROR: could not generate CSRF token - please check OpenSSL library integrity!');

				// something went wrong; OpenSSL is probably not present or < PHP5 installed
				return false;
			}
		}
		elseif(ctype_xdigit($token) && strlen($token) === 64) // check if valid token string format
		{
			// token already generated
			$newToken = $token;
		}

		// check if a new token has been fed in or generated
		if(!empty($newToken))
		{
			// token found, set as object token
			$this->token = $newToken;

			return true;
		}

		// something went wrong...
		return false;
	}

	// OTHER FUNCTIONS
	// SECURITY/SANATIZATION/VERIFICATION
	public function isValidString($string)
	{
		// checks if string is valid (i.e. not blank or null)
		// trim whitespace
		$string = trim($string);

		// check
		if(is_string($string) && !empty($string) && $string !== '\0') // protects from NULL byte vuln
		{
			// valid
			return true;
		}

		// invalid
		return false;
	}
	protected function generateCSRFToken()
	{
		// generates a cryptographically secure token used to prevent CSRF attacks
		// gathered from OpenSSL

		// "cyptographically-strong" variable must be passed by reference with openssl_random_psuedo_bytes(),
		// so cflag is set in its own variable
		$cryptographicallySecure = true;

		return bin2hex(openssl_random_pseudo_bytes(32, $cryptographicallySecure));
	}
}