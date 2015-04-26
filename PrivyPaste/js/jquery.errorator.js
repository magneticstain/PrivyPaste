/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Errorator - a jQuery library that includes functions or handling and displaying errors, both system and user-facing
 */

var errorMsgBox = $('#errorMsg');

function toggleErrorMsgBox(action)
{
    // show or hide error message container at top of page
    // options are 'show' to slideDown() and 'hide' to slideUp

    switch (action)
    {
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

function updateError(errorMsg)
{
    // update error message box with new error message
    errorMsgBox.html(errorMsg);
}