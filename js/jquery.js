/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
    Main jQuery - the starting point or all jquery actions
 */

$(document).ready(function(){
    // focus on main textarea on load of page
    setFocusToMainTextarea();

    // clear text in main textarea if default text, and replace if blank
    $('textarea#mainText').focus(function(){
        // send to update function
        updateMainTxt();
    }).blur(function(){
        // send to update function
        updateMainTxt();
    });

    // change opacity of upload button on hover
    $('div#textUploadButton').hover(function(){
        $(this).fadeTo(400, 1);
    },function(){
        $(this).fadeTo(400,.85);
    });
});