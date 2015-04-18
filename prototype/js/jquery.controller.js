/**
 *  Josh Carlson
 *  Created: 5/11/14
 *  Email: jcarlson@carlso.net
 */

/*
    Controller.js - The 'controller' portion of the underlying MVC framework. Responsible for manipulating data
 */

/* USER VISIBLE */
function changeUploadButton(newUploadButtonHTML, doFade)
{
    // change html inside upload button div to given text, with or without fading
//    console.log('NEW HTML:' + newUploadButtonHTML);

    // check for valid new html
    if(newUploadButtonHTML.trim())
    {
        // non-empty string, check for fading effects
        if(doFade === true)
        {
            // fade old html out and new html in
//            $('div#textUploadButton div').fadeOut('fast', function(){
            uploadButton.children().fadeOut('fast', function(){
                // after fading out, change html
                uploadButton.html(newUploadButtonHTML);
            }).fadeIn();
        }
        else
        {
            // switch html immediately
            uploadButton.html(newUploadButtonHTML);
        }

        return true;
    }

    return false;
}

/* BACKEND */
function sentTextToAPI(text)
{
    // sends a given text string to the paste creation api via ajax

}

function uploadText()
{
    // sends text data to api via ajax call
    var mainText = getMainTextarea();

    if(isValidText(mainText))
    {
        var uploadButtonMsgHTML = '' +
            '<img src="media/icons/text_send.gif" alt="Uploading you text, please wait..." />' +
            '<hr />' +
            '<p class="statusMsg">Uploading text, please wait...</p>';

        // update upload button
        changeUploadButton(uploadButtonMsgHTML, true);

        // send to api

        // update user if there's any error, else redirect to new paste

    }
}