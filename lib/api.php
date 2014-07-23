<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  lib/API.php -  a library containing the class Api() - an all-encompassing object with api-wide functions
	 * 						that other classes can inherit from. One of the components of the 'controller' portion of the underlying MVC
	 * 						framework.
	 */

	class Api extends Pastebin
	{
		private $db_conn;

		public function __construct(
			$db_conn
		)
		{
			if(
				!$this->setDbConn($db_conn)
			)
			{
				throw new Exception('FATAL ERROR: Api() -> could not create new api object!');
			}
		}

		/*
		 * NOTE: setters and getters are currently handled in Pastebin()
		 * If there are API specific object variables in the future, we will add them in here
		 */

		// OTHER FUNCTIONS
	}
?>