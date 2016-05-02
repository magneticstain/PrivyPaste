/**
 *  Josh Carlson
 *  Created: 5/11/14
 *  Email: jcarlson@carlso.net
 */

/*
    controller.js - The 'controller' portion of the underlying MVC framework. Responsible for manipulating and sending data
 */

/* USER VISIBLE */
function changeUploadButton(newUploadButtonHTML, doFade, f)
{
    // change html inside upload button div to given text, with or without fading

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

        // check for callback function
        if (typeof f == "function")
        {
            f();
        }

        return true;
    }

    return false;
}

/* BACKEND */
function getBaseUrl()
{
    // gets base URL string from HTML element
    return $('.base_url').text();
}

function sentTextToAPI(text)
{
    // sends a given text string to the paste creation api via ajax
    var pasteData = '',
        baseUrl = getBaseUrl(),
        textData = {
            'text': text
        };

    // send ajax request
    $.ajax({
        type: 'POST',
        url: baseUrl + 'api/v1/paste/add/',
        data: textData,
        dataType: 'json',
        success:
            function(pasteData)
            {
                // see if paste was successfully inserted
                if(pasteData.success) {
                    // redirect to paste URL
                    window.location.href = getBaseUrl() + 'paste/' + pasteData.paste_id;

                    return true;
                }
                else
                {
                    // something went wrong
                    // check if error message was set
                    if(pasteData.error)
                    {
                        // return api error for error message
                        updateError('The API seems to be having a problem. API returned error: ' + pasteData.error);
                    }
                    else
                    {
                        // return generalized error message
                        updateError('Something went catastrophically wrong with the API. Please contact your system administrator.');
                    }
                }
            },
        error:
            function()
            {
                // return error message noting the API was unreachable
                updateError('Uh oh! We couldn\'t reach the API. Please contact your system administrator.');
            }
    });

    // if we get here without redirecting to a new paste, something went wrong
    return false;
}

function uploadText()
{
    // sends text data to api via ajax call
    // save default html for upload button
    var defaultHtml = uploadButton.html();

    // get text from new text textarea
    var mainText = getMainTextareaVal();

    // see if valid text was submitted
    if(isValidText(mainText))
    {
        var uploadButtonMsgHTML = '' +
            '<img src="/PrivyPaste/media/icons/text_send.gif" alt="Uploading you text, please wait..." />' +
            '<hr />' +
            '<p class="statusMsg">Uploading text, please wait...</p>';

        // update upload button with status message
        changeUploadButton(uploadButtonMsgHTML, true);

        // send text to api
        var apiQueryResponse = sentTextToAPI(mainText);
        if(!apiQueryResponse)
        {
            // need to implement a timeout before updating the upload status message in case the upload button is still in it's fade action when the API returns
            setTimeout(function() {
                // reset upload button
                changeUploadButton(defaultHtml, false);
            }, 1000);

            // any error messages will be updated in sendTextToAPI() due to async properties of ajax
        }

        // if there were no errors when uploading text, then the page should automatically forward to the new text
    }
}