/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Main jQuery - the starting point or all jquery actions
 */

$(document).ready(function(){
    /*
        VIEW-ORIENTED LOGIC
     */

    // set error message box element for use with errorator.js functions
    var errorMsgDiv = $('#errorMsg');

    // display error message if it contains text
    checkForErrorMsg(errorMsgDiv);
    toggleErrorMsgBox(errorMsgDiv, 'show');

    // focus on main textarea on load of page
    setFocusToMainTextarea();

    // clear text in main textarea if default text, and replace if blank
    mainTextarea.focus(function(){
        // send to update function
        checkMainTextarea();
    }).blur(function(){
        // send to update function
        checkMainTextarea();
    });

    // change opacity of upload button on hover
    uploadButton.hover(function(){
        $(this).fadeTo(400, 1);
    },function(){
        $(this).fadeTo(400,.85);
    });

    /*
        CONTROLLER-ORIENTED LOGIC
     */
    // upload main text on click of upload button
    uploadButton.click(function(){
        uploadText();
    });
});