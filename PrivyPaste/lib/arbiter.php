<?php
	namespace privypaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  arbiter.php - a class containing logic related to user management
	 */

	class Arbiter
	{
		private $userId;
		private $token;
		private $username;
		private $accessLvl;

		public function __construct(
			$userId = 1,
			$token = '0000000000000000000000000000000000000000000000000000000000000000',
			$username = 'default',
			$accessLvl = 0
		)
		{
			if(
				!$this->setUserId($userId)
				|| !$this->setUsername($username)
				|| !$this->setAccessLvl($accessLvl)
			)
			{
				// something went wrong
				throw new \Exception('could not create new User() object!');
			}
		}

		// SETTERS
		public function setUserId($userId)
		{
			/*
			 *  Params:
			 *      - $userId
			 *          - ID for user account
			 *
			 *  Usage:
			 *      - verifies and sets the user's database record ID
			 *
			 *  Returns:
			 *      - boolean
			 */

			// must be int greater than zero
			$userId = (int) $userId;
			if(0 < $userId)
			{
				$this->userId = $userId;

				return true;
			}

			return false;
		}

		public function setToken($token)
		{
			/*
			 *  Params:
			 *      - $token
			 *          - unique token used to authenticate users and clients
			 *
			 *  Usage:
			 *      - verifies and sets the user's auth token
			 *
			 *  Returns:
			 *      - boolean
			 */

			// tokens are 256-bit hex strings, so should be 64 characters in length
			if(ctype_xdigit($token) && strlen($token) === 64)
			{
				$this->token = $token;

				return true;
			}

			return false;
		}

		public function setUsername($username)
		{
			/*
			 *  Params:
			 *      - $username
			 *          - username associated with account
			 *
			 *  Usage:
			 *      - verifies and sets a username
			 *
			 *  Returns:
			 *      - boolean
			 */

			// no restrictions other than it must be a string
			$this->username = (string)$username;

			return true;
		}

		public function setAccessLvl($accessLvl)
		{
			/*
			 *  Params:
			 *      - $accessLvl
			 *          - access level of user account
			 *
			 *  Usage:
			 *      - verifies and sets the user's access level
			 *      - access levels:
			 *          - 0 - Administrator/Root
			 *              - can view all pastes
			 *          - 1 - Generic User
			 *              - can view only their own pastes, or ones that have been set to public
			 *
			 *  Returns:
			 *      - boolean
			 */

			// must be within valid access level range
			if(0 <= $accessLvl && $accessLvl <= 1)
			{
				$this->accessLvl = $accessLvl;

				return true;
			}

			return false;
		}

		// GETTERS
		public function getUserId()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns user ID
			 *
			 *  Returns:
			 *      - int
			 */

			return $this->userId;
		}

		public function getUsername()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns username associated with account
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->username;
		}

		public function getAccessLvl()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns account access level
			 *
			 *  Returns:
			 *      - int
			 */

			return $this->accessLvl;
		}

		// OTHER FUNCTIONS
		static public function generateAuthToken()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - generates a 256-bit cryptographically-secure auth token used for authenticating users
			 *
			 *  Returns:
			 *      - string
			 */

			$token = bin2hex(openssl_random_pseudo_bytes(32));

			return $token;
		}
	}
?>