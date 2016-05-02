<?php
    namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  paste.php - library containing all objects related to pastes (e.g. raw text)
     */

    class Paste
    {
        protected $pasteId = 0;
	    public $pasteUid = '';
	    public $creationTime = 0;
	    public $lastModifiedTime = 0;
        protected $plaintext = '';
	    protected $ciphertext = '';
	    protected $iv = '';
	    private $logger;

        public function __construct(
	        $plaintext = '',
	        $ciphertext = '',
	        $pasteUid = '00000000',
	        $creationTime = 0,
	        $lastModifiedTime = 0,
	        $pasteId = 0,
	        $iv = '')
        {
            // set object vars
	        $this->logger = new Logger();

            if(
                !$this->setPasteId($pasteId)
                || !$this->setPasteUid($pasteUid)
                || !$this->setCreationTime($creationTime)
                || !$this->setLastModifiedTime($lastModifiedTime)
                || !$this->setPlaintext($plaintext)
	            || !$this->setCiphertext($ciphertext)
	            || !$this->setInitializationVector($iv)
            )
            {
                // something went wrong
                throw new \Exception('bad paste data supplied!');
            }
        }

        // Setters
        public function setPasteId($pasteId)
        {
            /*
             *  Params:
             *      - $pasteId
             *          - identifier for each paste
             *
             *  Usage:
             *      - verifies and sets the paste ID
             *
             *  Returns:
             *      - boolean
             */

            // normalize to integer
            $pasteId = (int) $pasteId;

            if(0 <= $pasteId)
            {
                // valid id
                $this->pasteId = $pasteId;

                return true;
            }

            return false;
        }

	    public function setPasteUid($pasteUid)
	    {
		    /*
			 *  Params:
			 *      - $pasteUid
			 *          - public unique identifier for each paste
			 *
			 *  Usage:
			 *      - verifies and sets the paste UID
			 *
			 *  Returns:
			 *      - boolean
			 */

		    // normalize to string
		    $pasteUid = (string) $pasteUid;

		    // check if UID is an alphanumeric string
		    // normally 8 chars, but adjustments may be made here to accommodate larger installs
		    if(ctype_alnum($pasteUid) && strlen($pasteUid) === 8)
		    {
			    // valid UID
			    $this->pasteUid = $pasteUid;

			    return true;
		    }

		    return false;
	    }

	    public function setCreationTime($creationTimestamp)
	    {
		    /*
			 *  Params:
			 *      - $creationTimestamp
			 *          - time of creation in epoch time
			 *
			 *  Usage:
			 *      - verifies and sets the creation date
			 *
			 *  Returns:
			 *      - boolean
			 */

		    // normalize to int
		    $creationTimestamp = (int) $creationTimestamp;

		    // timestamp should be now or before
		    $currentTime = time();
		    if($creationTimestamp <= $currentTime)
		    {
			    // valid creation time
			    $this->creationTime = $creationTimestamp;

			    return true;
		    }

		    return false;
	    }

	    public function setLastModifiedTime($lastModifiedTimestamp)
	    {
		    /*
			 *  Params:
			 *      - $lastModifiedTimestamp
			 *          - time of last modification in epoch time
			 *
			 *  Usage:
			 *      - verifies and sets the last modified date
			 *
			 *  Returns:
			 *      - boolean
			 */

		    // normalize to int
		    $lastModifiedTimestamp = (int) $lastModifiedTimestamp;

		    // timestamp should be before now or now
		    $currentTime = time();
		    if($lastModifiedTimestamp <= $currentTime)
		    {
			    // valid last modified time
			    $this->lastModifiedTime = $lastModifiedTimestamp;

			    return true;
		    }

		    return false;
	    }

        public function setPlaintext($plaintext)
        {
            /*
             *  Params:
             *      - $plaintext
             *          - plaintext (supplied by the user) for each paste
             *
             *  Usage:
             *      - verifies and sets the plaintext of the paste
             *
             *  Returns:
             *      - boolean
             */

            // normalize to string
            $plaintext = (string) $plaintext;

            // plaintext can be set to anything
            $this->plaintext = $plaintext;

            return true;
        }

	    public function setCiphertext($cipherText)
	    {
			/*
			*  Params:
			*      - $cipherText
			*          - ciphertext of $plaintext after being encrypted
			*
			*  Usage:
			*      - sets the ciphertext of the paste
			*
			*  Returns:
			*      - boolean
			*/

		    // normalize to string
		    $cipherText = (string) $cipherText;

		    // ciphertext can be set to anything
		    $this->ciphertext = $cipherText;

		    return true;
	    }

	    public function setInitializationVector($iv)
	    {
			/*
			*  Params:
			*      - $iv
			*          - initialization vector
			*
			*  Usage:
			*      - sets the encryption initialization vector for the paste
			*
			*  Returns:
			*      - boolean
			*/

		    // no restrictions at this time
		    $this->iv = $iv;

		    return true;
	    }

        // Getters
        public function getPasteId()
        {
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns the paste ID
			 *
			 *  Returns:
			 *      - int
			 */

            return $this->pasteId;
        }

	    public function getPasteUid()
	    {
			/*
			*  Params:
			*      - NONE
			*
			*  Usage:
			*      - returns the paste UID
			*
			*  Returns:
			*      - string
			*/

		    return $this->pasteUid;
	    }

	    public function getCreationTime()
	    {
			/*
			*  Params:
			*      - NONE
			*
			*  Usage:
			*      - returns the creation time in epoch time format
			*
			*  Returns:
			*      - int
			*/

		    return $this->creationTime;
	    }

	    public function getLastModifiedTime()
	    {
		    /*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns the last modified time in epoch time format
			 *
			 *  Returns:
			 *      - int
			 */

		    return $this->lastModifiedTime;
	    }

        public function getPlaintext()
        {
            /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns raw plaintext
             *
             *  Returns:
             *      - string
             */

            return $this->plaintext;
        }

	    public function getCiphertext()
	    {
		    /*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns ciphertext string
			 *
			 *  Returns:
			 *      - string
			 */

		    return $this->ciphertext;
	    }

	    public function getInitializationVector()
	    {
		    /*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - returns encryption initialization vector
			 *
			 *  Returns:
			 *      - blob
			 */

		    return $this->iv;
	    }

        // Other functions
	    // encryption and decryption
		public function encryptPlaintext()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - encrypt whatever is set as $this->plaintext to $this->ciphertext
			 *
			 *  Returns:
			 *      - boolean
			 */

			// get keys from file
			if($encKey = CryptKeeper::readKeyFromFile(ENC_KEY_FILE) && $hmacKey = CryptKeeper::readKeyFromFile(HMAC_KEY_FILE))
			{
				// encryption key and hmac key successfully read
				// generate IV (16 bytes for AES-256-CBC)
				$iv = CryptKeeper::generateInitializationVector(16);
				if($iv !== '')
				{
					// IV generated, encrypt text
					if($encryptedString = CryptKeeper::encryptString($encKey, $iv, $this->plaintext, $hmacKey))
					{
						// encryption was successful, set ciphertext as $this->ciphertext
						$this->setCiphertext($encryptedString);

						// also set IV
						$this->setInitializationVector($iv);

						return true;
					} else
					{
						// set error msg
						$this->logger->setLogMsg('paste encryption failed using given key ['.ENC_KEY_FILE.']');
					}
				}
				else
				{
					// set error msg
					$this->logger->setLogMsg('could not generate encryption IV');
				}
			}
			else
			{
				$this->logger->setLogMsg('could not read in the encryption and/or HMAC keys from file :: ENCRYPTION KEY FILE: [ '.ENC_KEY_FILE.' ] :: HMAC KEY FILE: [ '.HMAC_KEY_FILE.' ]');
			}

			// log an error
			$this->logger->setLogSrcFunction('Paste() -> encryptPlaintext()');
			$this->logger->writeLog();

			return false;
		}

	    public function decryptCiphertext()
	    {
		    /*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - decrypt whatever is set as $ciphertext as $plaintext
			 *
			 *  Returns:
			 *      - boolean
			 */

		    // get keys from file
		    if($encKey = CryptKeeper::readKeyFromFile(ENC_KEY_FILE) && $hmacKey = CryptKeeper::readKeyFromFile(HMAC_KEY_FILE))
		    {
			    // encryption key and hmac key successfully read, decrypt text
			    if($decryptedString = CryptKeeper::decryptString($encKey, $this->iv, $this->ciphertext, $hmacKey))
			    {
				    // decryption was successful, set plaintext as $this->plaintext
				    $this->setPlaintext($decryptedString);

				    return true;
			    }
			    else
			    {
				    $this->logger->setLogMsg('paste decryption failed using given key [ '.ENC_KEY_FILE.' ]');
			    }
		    }
		    else
		    {
			    $this->logger->setLogMsg('could not read in encryption and/or HMAC keys from file :: ENCRYPTION KEY FILE: [ '.ENC_KEY_FILE.' ] :: HMAC KEY FILE: [ '.HMAC_KEY_FILE.' ]');
		    }

		    // log an error
		    $this->logger->setLogSrcFunction('Paste() -> decryptCiphertext()');
		    $this->logger->writeLog();

		    return false;
	    }

	    // database syncing
	    public function sendPasteDataToDb($db)
	    {
			/*
			*  Params:
			*      - $dbConn
			*          - PDO object that acts as a connection to the backend database
			*
			*  Usage:
			*      - inserts $this->ciphertext into the database as a text record and returns the paste ID
			*
			*  Returns:
			*      - integer
			*/

		    // generate UID
		    $pasteUid = CryptKeeper::generateUniquePasteID();

		    // convert IV to hex string
		    $iv = bin2hex($this->iv);

		    if($pasteUid !== '')
		    {
			    // UID was successfully generated
			    // craft SQL query
			    $sql = "INSERT INTO pastes SET uid = :paste_uid, created = NOW(), last_modified = NOW(), ciphertext = :ciphertext, initialization_vector = :iv";

			    // set sql param array
			    $sqlParams = array(
				    'paste_uid' => $pasteUid,
				    'ciphertext' => $this->ciphertext,
				    'iv' => $iv
			    );

			    // execute and get result
			    $sqlResults = $db->queryDb($sql, 'insert', $sqlParams);

			    // execute query
			    if(1 <= $sqlResults)
			    {
				    // query executed successfully, return paste UID
				    return $pasteUid;
			    }
			    else
			    {
				    // set error msg
				    $this->logger->setLogMsg('could not insert paste into database :: [PUID: '.$pasteUid.']');
			    }
		    }
		    else
		    {
			    $this->logger->setLogMsg('could not generate a paste ID');
		    }

		    // log an error
		    $this->logger->setLogSrcFunction('Paste() -> sendPasteDataToDb()');
		    $this->logger->writeLog();

		    // if anything fails, return -1
		    return -1;
	    }

	    public function retrievePasteDataFromDb($db, $pasteUid)
	    {
		    /*
		     * Params:
			 *      - $dbConn
			 *          - PDO object that acts as a connection to the backend database
			 *      - $pasteUid
		     *          - UID of paste to retrieve
			 *
			 *  Usage:
			 *      - gets ciphertext of given paste UID
			 *
			 *  Returns:
			 *      - bool
		     */

		    // normalize paste UID as stirng
		    $pasteUid = (string) $pasteUid;

			// craft select sql query
		    $sql = "SELECT id, uid, created, last_modified, ciphertext, initialization_vector FROM pastes WHERE uid = :paste_uid LIMIT 1";

		    // set sql param array
		    $sqlParams = array(
			    'paste_uid' => $pasteUid
		    );

		    // execute and get result
		    $sqlResults = $db->queryDb($sql, 'select', $sqlParams);

		    // results should have exactly one row returned
		    if($sqlResults !== false && count($sqlResults) === 1)
		    {
				// got results
			    // convert IV from hex to binary data
			    $IV = hex2bin($sqlResults[0]['initialization_vector']);

			    // set result as respective object var and return true
			    $this->setPasteId($sqlResults[0]['id']);
			    $this->setPasteUid($sqlResults[0]['uid']);
			    $this->setCreationTime(strtotime($sqlResults[0]['created']));
			    $this->setLastModifiedTime(strtotime($sqlResults[0]['last_modified']));
			    $this->setCiphertext($sqlResults[0]['ciphertext']);
			    $this->setInitializationVector($IV);

			    return true;
		    }
		    else
		    {
			    // set error msg
			    $this->logger->setLogMsg('could not retrieve paste from database :: [PUID: '.$pasteUid.']');

			    // log an error
			    $this->logger->setLogSrcFunction('Paste() -> retrievePasteDataFromDb()');
			    $this->logger->writeLog();
		    }

		    return false;
	    }
    }

?>