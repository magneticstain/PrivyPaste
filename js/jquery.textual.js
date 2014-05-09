/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Textual - a jQuery library used in PrivyPaste or any text-related manipulations to the view
 */

function setFocusToMainTextarea()
{
    // puts th focus on the main textarea on the index page
    $('textarea#mainText').focus();

    // make sure to update the main text based on what's in it (usually clearing it if it's the initial load of the page)
    updateMainTxt();
}

function sendToMainTextarea(text)
{
    // update main text area on index page with given text string
    $('textarea#mainText').val(text);
}

function updateMainTxt()
{
    // update the main text area with the default text or no text, depending on the current text
    var currentTxt = $('textarea#mainText').val();

    if(currentTxt === 'Enter your text here!')
    {
        // default text, clear it out
        sendToMainTextarea('');
    }
    else if(currentTxt === '')
    {
        // no input, replace with default text
        sendToMainTextarea('Enter your text here!');
    }
}