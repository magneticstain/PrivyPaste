<?php
namespace privypaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/add - api feature to insert a new, encrypted paste into the database
	 */

    echo $_SERVER['DOCUMENT_ROOT'].'<br />';

    echo __DIR__;

	// create paste obj
    try
    {
        $paste = new Paste();

        echo '<pre>';
        var_dump($paste);
        echo '</pre>';
    }
    catch (\Exception $e)
    {
        echo 'ERROR: '.$e;
    }
?>