/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Errorator - a jQuery library that includes functions or handling and displaying errors, both system and user-facing
 */

function toggleErrorMsgBox(errorMsgBox, action) {
    // show or hide error message container at top of page
    // options are 'show' to slideDown() and 'hide' to slideUp

    switch (action) {
        case 'show':
            errorMsgBox.slideDown();

            break;

        case 'hide':
            errorMsgBox.slideUp();

            break;

        default:
            return false;
    }

    return true;
}

function updateError(errorMsgBox, errorMsg) {
    // update error message box with new error message
    errorMsgBox.html(errorMsg);
}

function checkForErrorMsg(errorMsgBox) {
    // checks if errorMsgBox contains text. If it does, show it to user
    var errorMsgBoxHtml = errorMsgBox.text();
    if(errorMsgBox !== '')
    {
        // display error message
        toggleErrorMsgBox(errorMsgBox, 'show');
    }
}