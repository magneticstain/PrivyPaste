<?php
	namespace privypaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  logger.php - class for logging appliation logs
	 */

	class Logger
	{
		protected $logMsg;
		protected $logSrcFunction;
		protected $logLvl;
		protected $logEmail;
		protected $logFile;

		public function __construct($logMsg = '', $logSrcFunction = 'Logger()', $logLvl = 0, $logEmail = '', $logFile = '')
		{
			/*
			 * Params:
			 *      - $logMsg
			 *          - message to be sent to log
			 *      - $logLvl
			 *          - logging level assigned to log
			 *      - $logEmail
			 *          - email for log message to be sent to (opt)
			 *      - $logFile
			 *          - file to write log message to
			 *
			 *  Usage:
			 *      - sets variables of and creates a new Logger() object
			 *
			 *  Returns:
			 *      - NONE
			 */

			if(
				!$this->setLogMsg($logMsg)
				|| !$this->setLogSrcFunction($logSrcFunction)
				|| !$this->setLogLvl($logLvl)
				|| !$this->setLogEmail($logEmail)
				|| !$this->setLogFile($logFile)
			)
			{
				throw new \Exception('invalid log info supplied!');
			}
		}

		# SETTERS
		public function setLogMsg($logMsg)
		{
			/*
			 * Params:
			 *      - $logMsg
			 *          - message to be written to log
			 *
			 *  Usage:
			 *      - sets log message variable of Logger() object
			 *
			 *  Returns:
			 *      - bool
			 */

			// no restraints on what log message can be
			$this->logMsg = $logMsg;

			return true;
		}

		public function setLogSrcFunction($logSrcFunc)
		{
			/*
			 * Params:
			 *      - $logSrcFunc
			 *          - function that has generated the log
			 *
			 *  Usage:
			 *      - sets log source function variable of Logger() object
			 *
			 *  Returns:
			 *      - bool
			 */

			// only restriction is that is must be set
			if(isset($logSrcFunc) && !empty($logSrcFunc))
			{
				$this->logSrcFunction = $logSrcFunc;

				return true;
			}

			return false;
		}

		public function setLogLvl($logLvl)
		{
			/*
			 * Params:
			 *      - $logLvl
			 *          - level of logging associated with the log message
			 *
			 *  Usage:
			 *      - sets log level variable of Logger() object
			 *
			 *  Returns:
			 *      - bool
			 */

			// normalize $logLvl
			$logLvl = (int)$logLvl;

			// valid log levels are [0-4]
			if(0 <= $logLvl && $logLvl <= 4)
			{
				$this->logLvl = $logLvl;

				return true;
			}

			return false;
		}

		public function setLogEmail($logEmail)
		{
			/*
			 * Params:
			 *      - $logEmail
			 *          - destination email for log message
			 *
			 *  Usage:
			 *      - sets log email variable of Logger() object
			 *
			 *  Returns:
			 *      - bool
			 */

			// log email is optional, so values can be valid emails or blank
			if($logEmail === '' || filter_var($logEmail, FILTER_VALIDATE_EMAIL))
			{
				$this->logEmail = $logEmail;

				return true;
			}

			return false;
		}

		public function setLogFile($logFilename)
		{
			/*
			 * Params:
			 *      - $logFilename
			 *          - file to write log message to
			 *
			 *  Usage:
			 *      - sets log file variable of Logger() object
			 *
			 *  Returns:
			 *      - bool
			 */

			// if file exists, check to see if it is writable
			if(file_exists($logFilename) && !is_writable($logFilename))
			{
				return false;
			}
			else
			{
				// file doesn't exists OR it exists and is writable
				$this->logFile = $logFilename;

				return true;
			}
		}

		# GETTERS
		public function getLogMsg()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - retrieves and returns log message variable of Logger() object
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->logMsg;
		}

		public function getLogSrcFunction()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - retrieves and returns log source function variable of Logger() object
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->logSrcFunction;
		}

		public function getLogLvl()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - retrieves and returns log level variable of Logger() object
			 *
			 *  Returns:
			 *      - int
			 */

			return $this->logLvl;
		}

		public function getLogEmail()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - retrieves and returns log's destination email variable of Logger() object
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->logEmail;
		}

		public function getLogFile()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - retrieves and returns log filename variable of Logger() object
			 *
			 *  Returns:
			 *      - string
			 */

			return $this->logFile;
		}

		# OTHER FUNCTIONS
		public function writeLog()
		{
			/*
			 * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - writes log to destination
			 *          - writes to default error log
			 *          - writes to log file is specified OR email if specified
			 *
			 *  Returns:
			 *      - bool
			 */

			# add default formatting to log message
			$logMessage = 'PrivyPaste :: '.$this->logSrcFunction.' :: '.$this->logMsg;

			# send error via email if email is not blank and log level is set to 1
			if($this->logEmail !== '' && $this->logLvl === 3)
			{
				// email error log
				error_log($logMessage, $this->logLvl, $this->logEmail);
			}

			# send error to specific filename if it's set and log level is set to 3, default if not
			if($this->logFile !== '' && $this->logLvl == 3)
			{
				# write to specific file
				error_log($logMessage, $this->logLvl, $this->logFile);
			}
			else
			{
				# write to default error file set in web server config
				error_log($logMessage, $this->logLvl);
			}
		}
	}
?>