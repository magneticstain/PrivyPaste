/**
 *  Josh Carlson
 *  Created: 5/11/14
 *  Email: jcarlson@carlso.net
 */

/*
    Controller.js - The 'controller' portion of the underlying MVC framework. Responsible for manipulating data
 */

/* USER VISIBLE */
function changeUploadButton(newUploadButtonHTML, doFade, f)
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
    var pasteData = '';

    // sends a given text string to the paste creation api via ajax
    var baseUrl = getBaseUrl(),
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
                if(pasteData.success)
                {
                    // update upload button w/ url or display error
                    if(pasteData.error)
                    {
                        // display error
                        updateError('The API seems to be having a problem. ERROR: ' + pasteData.error);

                        return false;
                    }
                    else
                    {
                        // redirect to paste URL
                        //var pasteUrl = getBaseUrl() + '?p=' + pasteData.paste_id;
                        window.location.href = getBaseUrl() + '?p=' + pasteData.paste_id;

                        return true;
                    }
                }
            },
        error:
            function()
            {
                // update user w/ error
                updateError('Uh oh! We couldn\'t reach the API. Please contact your system administrator.');

                return false;
            }
    });
}

function uploadText()
{
    // save default html
    var defaultHtml = uploadButton.html();

    // sends text data to api via ajax call
    var mainText = getMainTextareaVal();

    if(isValidText(mainText))
    {
        var uploadButtonMsgHTML = '' +
            '<img src="media/icons/text_send.gif" alt="Uploading you text, please wait..." />' +
            '<hr />' +
            '<p class="statusMsg">Uploading text, please wait...</p>';

        // update upload button
        changeUploadButton(uploadButtonMsgHTML, true);

        // send to api
        if(!sentTextToAPI(mainText))
        {
            // need to implement a timeout before updating the timeout in case the upload button is still in it's fade action when the API returns
            setTimeout(function() {
                // reset upload button
                changeUploadButton(defaultHtml, false);
            }, 1000);
        }
    }
}