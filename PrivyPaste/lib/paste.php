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
        protected $plaintext = '';
	    protected $ciphertext = '';

        public function __construct($plaintext = '', $pasteId = 0)
        {
            // set vars
            if(
                !$this->setPasteId($pasteId)
                || !$this->setPlaintext($plaintext)
            )
            {
                // something went wrong
                throw new \Exception('bad paste ID or plaintext supplied!');
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
//				else
//				{
//					echo "[DEBUG] ERROR: could not encrypt text with given public key!\n";
//				}
			}
//			else
//			{
//				echo "[DEBUG] ERROR: could not read public key!\n";
//			}

			return false;
		}

	    public function sendCiphertextToDb($dbConn)
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
			*      - boolean
			*/

		    $ciphertext = '';

		    // craft SQL query
		    $sql = "INSERT INTO pastes (ciphertext) VALUES (:ciphertext)";

		    // prepare query
		    $dbStmt = $dbConn->prepare($sql);

		    // bind params
		    $dbStmt->bindParam('ciphertext', $ciphertext);

		    // execute query
		    $ciphertext = $this->ciphertext;
		    if($dbStmt->execute())
		    {
			    // query executed successfully, return paste id
			    $pasteId = $dbConn->lastInsertId();

			    return $pasteId;
		    }

		    // if anything fails, return -1 as a string val
		    return '-1';
	    }
    }

?>