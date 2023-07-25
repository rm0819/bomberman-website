

function popUp(URL) {
	day = new Date();
	id = day.getTime();
	window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=550,height=650,left=540,top=250');
}

document.querySelector("#moreInfoButton").addEventListener("click", e => {
    popUp("user_enumeration_info.php");
 });