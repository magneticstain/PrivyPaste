<?php
/**
 *  PrivyPaste
 *  Author: Josh Carlson
 *  Email: jcarlson(at)carlso(dot)net
 */

/*
 *  lib/Paste - library containing all objects related to pastes (e.g. raw text)
 */

namespace privypaste;


class Paste
{
    protected $pasteId = 0;
    protected $plaintext = '';

    public function __construct($plaintext)
    {

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

        if($pasteId > 0)
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

        if($plaintext !== '')
        {
            // valid
            $this->plaintext = $plaintext;

            return true;
        }

        return false;
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

        return $this->$plaintext;
    }

    // Other functions

}