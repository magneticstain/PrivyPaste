/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Textual - a jQuery library used in PrivyPaste or any text-related manipulations to the view
 */

function isValidText(rawText)
{
    // checks if the given text is valid (i.e. not blank, null, etc)

    // trim whitespace
    var trimmedText = rawText.trim();

    // check if blank
    if(trimmedText !== '' && trimmedText !== null)
    {
        // valid
        return true;
    }

    // invalid
    return false;
}

function setFocusToMainTextarea()
{
    // puts th focus on the main textarea on the index page
    mainTextarea.focus();

    // make sure to update the main text based on what's in it (usually clearing it if it's the initial load of the page)
    checkMainTextarea();
}

function getMainTextareaVal()
{
    // returns text from the main textarea
    // should never be null
    var mainText = mainTextarea.val();

    if(typeof mainText !== null)
    {
        return mainText;
    }
    else
    {
        return '';
    }
}

function sendToMainTextarea(newText)
{
    // update main text area on index page with given text string
    mainTextarea.val(newText);
}

function checkMainTextarea()
{
    // update the main text area with the default text or no text, depending on the current text. usually performed on
    // focus or blur of textarea
    var currentText = getMainTextareaVal(),
        defaultText = 'Enter your text here!',
        newText = '';

    // check if the text is blank
    if(currentText === '')
    {
        // no input, replace with default text
        newText = defaultText;
    }

    sendToMainTextarea(newText);
}