// These functions need to be called after the content they reference
// has been added to the page otherwise they will fail.

function addEventListeners() {
	var source_button = document.getElementById ("source_button");

	if (source_button) {
		source_button.addEventListener("click", function() {
			var url=source_button.dataset.sourceUrl;
			popUp (url);
		});
	}

	var help_button = document.getElementById ("help_button");

	if (help_button) {
		help_button.addEventListener("click", function() {
			var url=help_button.dataset.helpUrl;
			popUp (url);
		});
	}

	var info_button = document.getElementById ("info_button");

	if (info_button) {
		info_button.addEventListener("click", function() {
			var url=info_button.dataset.infoUrl;
			newPopUp (url);
		});
	}
}

addEventListeners();

document.querySelector("#continueToSQL").addEventListener("click", e => {
	window.location.href = "index.php";
});
