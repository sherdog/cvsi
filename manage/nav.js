function showSub(section)
{

	if(document.getElementById(section).style.display == "none"){
		document.getElementById(section).style.display = "block";
		document.getElementById("menucontrol-"+section).innerHTML="[collapse]";
	}else{
		document.getElementById(section).style.display = "none";
		document.getElementById("menucontrol-"+section).innerHTML="[expand]";
	}
}

function setSection(area){
	var area = area;
	document.getElementById(area).style.display = "block";
	document.getElementById("menucontrol-"+area).innerHTML="[collapse]";
}