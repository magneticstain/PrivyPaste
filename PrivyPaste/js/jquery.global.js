/**
 *  Josh Carlson
 *  Created: 5/7/14
 *  Email: jcarlson@carlso.net
 */

/*
 Global.js - storage place or all global vars/functions, normally used for select shortcuts
 */

// must declare a blank string variable as variable prototypes to satisfy functions that use this, as well as wait until
// the DOM is loaded so that the proper elements can be selected
var mainTextarea = null,
    uploadButton = null;

$(document).ready(function(){
    // [index] upload button
    uploadButton = $('#textUploadButton');

    // [index] main text area
    mainTextarea = $('#mainText');
});