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
	    private $iv = '';
	    private $logger;

        public function __construct($plaintext = '', $ciphertext = '', $pasteUid = '00000000', $creationTime = 0, $lastModifiedTime = 0, $pasteId = 0, $iv = '')
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
             *      - $pasteId [INT]: unique identifier for each paste
             *
             *  Usage:
             *      - verifies and sets the paste ID
             *
             *  Returns:
             *      - boolean
             */

            // normalize to integer
            $pasteId = (int) $pasteId;

            if($pasteId >= 0)
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
			 *      - $pasteUid [STRING]
			 *          - unique identifier for each paste
			 *
			 *  Usage:
			 *      - verifies and sets the paste UID
			 *
			 *  Returns:
			 *      - boolean
			 */

		    // normalize to string
		    $pasteUid = (string) $pasteUid;

		    // UID is an alphanumeric string
		    // normally 8 chars, but adjustments may be made to accomedate larger installs
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
			 *      - $creationTimestamp [INT]
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

		    // timestamp should be before now
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
			 *      - $lastModifiedTimestamp [INT]
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

		    // timestamp should be before now
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
             *      - $plaintext [STRING]: plaintext (supplied by the user) for each paste
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
			*      - $cipherText [STRING]: ciphertext of $plaintext after being encrypted
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
			*      - $iv [BLOB]: initialization vector
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
		public function encryptPlaintext()
		{
			/*
			 *  Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - encrypt whatever is set as $plaintext to $ciphertext
			 *
			 *  Returns:
			 *      - boolean
			 */

			// get keys from file
			if($encKey = CryptKeeper::readKeyFromFile(ENC_KEY_FILE) && $hmacKey = CryptKeeper::readKeyFromFile(HMAC_KEY_FILE))
			{
				// encryption key and hmac key successfully read, encrypt text using key
				// generate IV (16 bytes)
				$iv = CryptKeeper::generateInitializationVector(16);
				if($iv !== '')
				{
//					echo "DEBUG :: ".$this->plaintext." :: ".bin2hex($key)." :: ".bin2hex($iv);
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
				$this->logger->setLogMsg('could not read in key from file [ '.ENC_KEY_FILE.' ]');
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

		    // get key from file
		    if($encKey = CryptKeeper::readKeyFromFile(ENC_KEY_FILE) && $hmacKey = CryptKeeper::readKeyFromFile(HMAC_KEY_FILE))
		    {
//			    echo "DEBUG :: ".$this->ciphertext." :: ".bin2hex($encKey)." :: ".bin2hex($this->iv);
			    // encryption key and hmac key successfully read, decrypt text
			    if($decryptedString = CryptKeeper::decryptString($encKey, $this->iv, $this->ciphertext, $hmacKey))
			    {
				    // decryption was successful, set plaintext as $this->plaintext
				    $this->setPlaintext($decryptedString);

				    return true;
			    }
			    else
			    {
				    // set error msg
				    $this->logger->setLogMsg('paste decryption failed using given key [ '.ENC_KEY_FILE.' ]');
			    }
		    }
		    else
		    {
			    $this->logger->setLogMsg('could not read in key from file ['.ENC_KEY_FILE.']');
		    }

		    // log an error
		    $this->logger->setLogSrcFunction('Paste() -> decryptCiphertext()');
		    $this->logger->writeLog();

		    return false;
	    }

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

		    // get UID
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
			    if($sqlResults >= 1)
			    {
				    // query executed successfully, return paste UID
				    return $pasteUid;
			    }
			    else
			    {
				    // set error msg
				    $this->logger->setLogMsg('could not insert paste into database [PUID: '.$pasteUid.']');
			    }
		    }
		    else
		    {
			    $this->logger->setLogMsg('could not generate a paste ID');
		    }

		    // log an error
		    $this->logger->setLogSrcFunction('Paste() -> sendPasteDataToDb()');
		    $this->logger->writeLog();

		    // if anything fails, return -1 as a string val
		    return '-1';
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

			// craft select sql query
		    $sql = "SELECT id, uid, created, last_modified, ciphertext, initialization_vector FROM pastes WHERE uid = :paste_uid LIMIT 1";

		    // set sql param array
		    $sqlParams = array(
			    'paste_uid' => $pasteUid
		    );

		    // execute and get result
		    $sqlResults = $db->queryDb($sql, 'select', $sqlParams);
		    if($sqlResults !== false && count($sqlResults) === 1)
		    {
				// got results
			    // set result as respective object var and return true
			    $this->setPasteId($sqlResults[0]['id']);
			    $this->setPasteUid($sqlResults[0]['uid']);
			    $this->setCreationTime(strtotime($sqlResults[0]['created']));
			    $this->setLastModifiedTime(strtotime($sqlResults[0]['last_modified']));
			    $this->setCiphertext($sqlResults[0]['ciphertext']);
			    $this->setInitializationVector(hex2bin($sqlResults[0]['initialization_vector']));

			    return true;
		    }
		    else
		    {
			    // set error msg
			    $this->logger->setLogMsg('could not retrieve paste from database [PUID: '.$pasteUid.']');

			    // log an error
			    $this->logger->setLogSrcFunction('Paste() -> retrievePasteDataFromDb()');
			    $this->logger->writeLog();
		    }

		    return false;
	    }
    }

?>