<script>
function makeurl(theurl,thetype) {
 if (theurl.indexOf("?") < 0) {
            theurl = theurl + "?sa=1&type=" + thetype;
       }
       else {
            theurl = theurl + "&type=" + thetype;
       }
	   document.write('<a href=\"http://<?php echo $_SERVER['HTTP_HOST'];?>'+theurl+'\"><img src=\"img/'+thetype+'.gif\" width=\"16\" height=\"16\" alt=\"'+thetype+'\" /></a>');
}
</script>
<div align="center">
<?php echo TXT_SELECT_DIR;?><br>
<script>makeurl('<?php echo $_SERVER['REQUEST_URI'];?>','image');</script>&nbsp;&nbsp;<script>makeurl('<?php echo $_SERVER['REQUEST_URI'];?>','media');</script>&nbsp;&nbsp;<script>makeurl('<?php echo $_SERVER['REQUEST_URI'];?>','file');</script>
</div>