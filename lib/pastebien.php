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
	protected $userId;
	protected $token;
	protected $username;

	public function __construct()
	{

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