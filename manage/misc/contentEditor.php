<script language="javascript">
  tinyMCE.init({
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	theme : "advanced",
	// Theme options
	//plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
	plugins : "media,example,table,inlinepopups,imagemanager,advlink,contextmenu,preview,fullscreen",
	theme_advanced_buttons1 : "mylistbox,removeformat, bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,link,unlink,|,classSelector,template,insertimage,media,preview,fullscreen,code",
	//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor, image, cleanup, code",
	//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|sub,sup",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	inline_styles : true,
	theme_advanced_resizing : true,
	forced_root_block : '',
	keep_styles : true,
	inline : "yes",
	template_templates: 
	[
	<?
	/* Output all template here. */
	$templateResults = dbQuery('SELECT * FROM email_templates');
	$templateCount = dbNumRows($templateResults);
	$rowCount = 1;
	while($template = dbFetchArray($templateResults)) {
		//output the template stuff!
		echo "{\n";
		echo "title : '" .output($template['email_templates_name'])."',\n";
		echo "src : 'communication_template_loader.php?id=".$template['email_templates_id']."',\n";
		echo "description: '" . output($template['email_templates_desc'])."'\n";
		if($rowCount == $templateCount) echo "}\n"; else echo "},\n"; //determines if we are at the last record (we don't want to teh comma on the last record.
		
	$rowCount++;
	}
	?>
	],
	formats : {
		heading_text : { inline : 'span', styles : { color : '#6c522c', fontSize : '18px', fontWeight : 'normal'} },
		subheading_text : { inline : 'span', styles : { color : '#333333', fontSize : '14px', fontWeight : 'bold'} },
		reg_text : { inline : 'span', styles : { color : '#1a1a1a', fontSize : '12px', fontWeight : 'normal'} }

	},
	
	// Example content CSS (should be your site CSS)
	content_css : "css/cms.css",
	width: "650",
	remove_script_host : false,
	relative_urls : false,
	height: "500"
	
});

tinymce.create('tinymce.plugins.ExamplePlugin', {
    createControl: function(n, cm) {
        switch (n) {
            case 'mylistbox':
                var mlb = cm.createListBox('mylistbox', {
                     title : 'Format',
					 max_width : 300,
                     onselect : function(v) {
						 //check to see if val = val if it is, then we remove formatting else we add it!
						if(mlb===v) {
							tinymce.activeEditor.formatter.remove(v)
						} else {
							tinyMCE.activeEditor.formatter.apply(v);
						}
                     }
                });
				 mlb.onRenderMenu.add(function(c, m) {
                    m.settings['max_width'] = 300;
                });

                // Add some values to the list box
                mlb.add('Heading Text', 'heading_text');
                mlb.add('Subheading Text', 'subheading_text');
                mlb.add('Paragraph Text', 'reg_text');

                // Return the new listbox instance
                return mlb;
        }

        return null;
    }
});

// Register plugin with a short name
tinymce.PluginManager.add('example', tinymce.plugins.ExamplePlugin);


//add block section!
function addBlock(id) {
	var _id = id;//Grabs the corect html
	$.get("__getContentBlock.php", { id: _id },
	   function(data){
		tinyMCE.execCommand('mceInsertContent',false, data)
	  });

	
}

</script>