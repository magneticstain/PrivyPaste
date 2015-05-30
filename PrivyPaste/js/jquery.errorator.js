/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    errorator.js - a jQuery library that includes functions or handling and displaying errors, both system and user-facing
 */

function toggleErrorMsgBox(action) {
    // show or hide error message container at top of page
    // options are 'show' to slideDown() and 'hide' to slideUp

    switch (action) {
        case 'show':
            errorMsgDiv.slideDown(500);

            break;

        case 'hide':
            errorMsgDiv.slideUp();

            break;

        default:
            return false;
    }

    return true;
}

function updateError(errorMsg) {
    // update error message box with new error message
    errorMsgDiv.html(errorMsg);

    toggleErrorMsgBox('show');
}

function checkForErrorMsg() {
    // checks if errorMsgBox contains text. If it does, show it to user
    var errorMsgBoxHtml = errorMsgDiv.text();
    if(errorMsgBoxHtml !== '')
    {
        // display error message
        toggleErrorMsgBox('show');
    }
}