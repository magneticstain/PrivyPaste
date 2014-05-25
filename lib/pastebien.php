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
		$token = null,
		$userId = 0,
		$username = null
	)
	{
		if(
			!$this->setToken($token)
			|| !$this->setUserId($userId)
			|| !$this->setUsername($username)
		)
		{
			// invalid; throw exception
			throw new Exception('ERROR: could not create new pastebin!');
		}
	}

	// GETTERS
	public function getUserId()
	{
		return $this->userId;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getUsername()
	{
		return $this->username;
	}

	// SETTERS
	public function setUserId($userId)
	{
		// user id must be a numeral up to 11 digits long (due to db constraints)
		if(is_numeric($userId) && ($userId > 0 && $userId < 100000000000))
		{
			// valid
			$this->userId = $userId;

			return true;
		}

		// invalid
		return false;
	}

	public function setToken($token)
	{
		// token is a 16 byte (128-bit) hex string of random bytes generated from OpenSSL

		// try to get token
		if($newToken = $this->generateCSRFToken())
		{
			// successfully generated
			$this->token = $newToken;

			return true;
		}

		// something went wrong; OpenSSL is probably not present or < PHP5 installed
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
		}

		// invalid
		return false;
	}

	// OTHER FUNCTIONS


	protected function generateCSRFToken()
	{
		// generates a cryptographically secure token used to prevent CSRF attacks
		// gathered from OpenSSL

		// "cyptographically-strong" variable must be passed by reference with openssl_random_psuedo_bytes(),
		// so cflag is set in its own variable
		$cryptographicallySecure = true;

		return bin2hex(openssl_random_pseudo_bytes(16, $cryptographicallySecure));
	}

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
}