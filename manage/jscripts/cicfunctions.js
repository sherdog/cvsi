	function str_replace(haystack, needle, replacement) {
		var temp = haystack.split(needle);
		return temp.join(replacement);
	}
	
	function cleanURLField(str){
		//k we are going to make sure there are no funny characters in the title duh.
		var string = str_replace(str, '!', '' );
		string = str_replace(string, '@', '' );
		string = str_replace(string, '?', '' );
		string = str_replace(string, '/', '' );
		string = str_replace(string, '\\', '' );
		string = str_replace(string, '|', '' );
		string = str_replace(string, ']', '' );
		string = str_replace(string, '[', '' );
		string = str_replace(string, '@', '' );
		string = str_replace(string, '#', '');
		string = str_replace(string, '$', '');
		string = str_replace(string, '%', '');
		string = str_replace(string, '^', '');
		string = str_replace(string, '&', '');
		string = str_replace(string, '*', '');
		string = str_replace(string, '(', '');
		string = str_replace(string, ')', '');
		string = str_replace(string, '.', '');
		string = str_replace(string, ',', '');
		string = str_replace(string, ' ', '_');
		string = str_replace(string, "'", "");
		string = string.toLowerCase();
		return string;
	}
