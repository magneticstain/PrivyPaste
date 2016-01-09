<?php
	namespace privypaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  user.php - a class containing logic related to user management
	 */

	class User
	{
		private $username;
		private $email;
		private $accessLvl;

		public function __construct($username = 'default', $email = 'default@web.com', $accessLvl = 0)
		{
			if(
				!$this->setUsername($username)
				|| !$this->setEmail($email)
				|| !$this->setAccessLvl($accessLvl)
			)
			{
				// something went wrong
				throw new \Exception('could not create new User() object!');
			}
		}

		// SETTERS
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

		public function setEmail($email)
		{
			/*
			 *  Params:
			 *      - $email
			 *          - email address associated with account
			 *
			 *  Usage:
			 *      - verifies and sets an email address
			 *
			 *  Returns:
			 *      - boolean
			 */

			// must be a valid email address format
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$this->email = $email;

				return true;
			}

			return false;
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

		public function getEmail()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns email address associated with account
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->email;
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
	}
?>