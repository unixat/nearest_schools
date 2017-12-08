<!DOCTYPE html>
<html>
<head>
	<script>
	function init() 
	{
		var postcode = document.getElementById('postcode');
		var maxDistance = document.getElementById('maxDistance');
		var errorDiv = document.getElementById('error');
		var form = document.getElementById('form');

		if (errorDiv) {
	   		errorDiv.style.display = "block";
		}

		function hideErrorMsg() {
			if (errorDiv) errorDiv.style.display = 'none';
		}

		function validatePostcode(e) {
			var postcodeVal = postcode.value;
			e.preventDefault();
			if (typeof postcode === 'undefined' || postcodeVal.length < 6 || postcodeVal.length > 9) {
				alert('postcode length is invalid');
				return false;
			}
			return true;
		}

		if (document.body.attachEvent)
		    postcode.attachEvent("onclick", hideErrorMsg);
		else
		    postcode.addEventListener("click", hideErrorMsg);

		if (document.body.attachEvent)
		    form.attachEvent("onsubmit", validatePostcode);
		else
		    form.addEventListener("submit", validatePostcode);
	}

	</script>

	<meta charset="utf-8" />
	<style>
		body {
			font-family: sans-serif;
		}
		.field {
			margin:0.4em 0em;
		}
		a { 
			margin-bottom:0.5em; 
		}
		h2 {
			margin:0.1em 0 0.3em 0;
		}
		label {
			width:7em;
			display:inline-block;
		}
		input[type='text'] {
			width:7em;
			text-transform:uppercase;
		}
		input[type='submit'] {
			margin-top:0.5em;
			background:#8c0;
			padding:3px;
			width:7em;
			font-weight:bold;
			font-size:14px;
		}
		input[type='submit']:hover {
			background:#8e0;
		}
		div,school {
			margin-top:0.5em;
		}
		.school h2 {
			margin:0;
			margin-top:0.5em;
			font-size:14px;
		}
		.school p {
			margin:0;
			padding-left:2em;
		}
		.error {
			color:red;
		}
	</style>
</head>
<body onload="init();" >
	<h2>Nearest Schools Finder</h2>
	<a href="index.php">Search</a>
