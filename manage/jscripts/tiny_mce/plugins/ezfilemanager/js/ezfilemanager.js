function stopError() {
return true;
} 
stopError();
var URL='';
function selectURL(url)
{
	URL = url;// insert the url in the global var
	FileBrowserDialogue.mySubmit();
}
var FileBrowserDialogue = {
    init : function () {
    },
    mySubmit : function () {
	try {// catch error if image/link/media popup is not open
 		 var win = tinyMCEPopup.getWindowArg("window");
        // insert information now
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
		// for image browsers: update image dimensions
		  if (document.URL.indexOf('type=image') != -1)
			  {
	        if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
	        if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
			  }
        // close popup window
        tinyMCEPopup.close();
		} catch (e) { }
    }
}
try {
tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
} catch (e) { }
