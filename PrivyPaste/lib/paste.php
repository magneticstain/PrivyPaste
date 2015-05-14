<?php
    namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  lib/paste.php - library containing all objects related to pastes (e.g. raw text)
     */

    class Paste
    {
        protected $pasteId = 0;
        protected $plaintext = '';
	    protected $ciphertext = '';

        public function __construct($plaintext = '', $ciphertext = '', $pasteId = 0)
        {
            // set vars
            if(
                !$this->setPasteId($pasteId)
                || !$this->setPlaintext($plaintext)
	            || !$this->setCiphertext($ciphertext)
            )
            {
                // something went wrong
                throw new \Exception('bad paste ID, ciphertext or plaintext supplied!');
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

			// get public key from file
//			echo "[DEBUG] PUBLIC KEY FILE: ".PUBLIC_KEY."\n";
			if($publicKey = CryptKeeper::getPkiKeyFromFile('public', PUBLIC_KEY))
			{
				// public key successfully read, encrypt text using key
				if($encryptedString = CryptKeeper::encryptString($publicKey, $this->plaintext, true))
				{
					// encryption was successful, set ciphertext as $this->ciphertext
					$this->setCiphertext($encryptedString);

					return true;
				}
			}

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

		    // get private key from file
		    if($privateKey = CryptKeeper::getPkiKeyFromFile('private', PRIVATE_KEY))
		    {
			    // private key successfully read, decrypt text
			    if($decryptedString = CryptKeeper::decryptString($privateKey, $this->ciphertext, true))
			    {
				    // decyption was successful, set plaintext as $this->plaintext
				    $this->setPlaintext($decryptedString);

				    return true;
			    }
		    }

		    return false;
	    }

	    public function sendCiphertextToDb($db)
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

		    $ciphertext = '';

		    // get UID
		    $pasteUid = CryptKeeper::generateUniquePasteID();

		    if($pasteUid !== '')
		    {
			    // UID was successfully generated
			    // craft SQL query
			    $sql = "INSERT INTO pastes SET uid = :paste_uid, created = NOW(), last_modified = NOW(), ciphertext = :ciphertext";

			    // set sql param array
			    $sqlParams = array(
				    'paste_uid' => $pasteUid,
				    'ciphertext' => $this->ciphertext
			    );

			    // execute and get result
			    $sqlResults = $db->queryDb($sql, 'insert', $sqlParams);

			    // execute query
			    if($sqlResults >= 1)
			    {
				    // query executed successfully, return paste UID
				    return $pasteUid;
			    }
		    }

		    // if anything fails, return -1 as a string val
		    return '-1';
	    }

	    public function retrieveCiphertextFromDb($db, $pasteUid)
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
		    $sql = "SELECT ciphertext FROM pastes WHERE uid = :paste_uid LIMIT 1";

		    // set sql param array
		    $sqlParams = array(
			    'paste_uid' => $pasteUid
		    );

		    // execute and get result
		    $sqlResults = $db->queryDb($sql, 'select', $sqlParams);
		    if($sqlResults !== false && count($sqlResults) === 1)
		    {
				// got results
			    // set result as ciphertext object var and return true
			    $this->ciphertext = $sqlResults[0]['ciphertext'];

			    return true;
		    }

		    return false;
	    }
    }

?>