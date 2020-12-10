var logInFormContent = `
Email or Username: <input id="emailOrUsernameField" type="text" name="emailOrUsername"><br>
Password: <input id="passwordField" type="password" name="password"><br>
<button>Submit</button>
`;

function createLogInForm() {
	$("#startForm").html(logInFormContent);
	$("#startForm").attr("onsubmit", "enter()");
}

function enter() {
	event.preventDefault(); // stop page from reloading
	var password = $("#passwordField").val();
	$.post("server/enter.php", {
		emailOrUsername: $("#emailOrUsernameField").val(),
		password: $("#passwordField").val(),
		}, function(data, status) {
			alert(data);
			console.log(data);
			var locationString = window.location + "";
			if(locationString.indexOf("index.html") != -1) {
				locationString = locationString.substr(0, locationString.indexOf("index.html"));
			}
			window.location = locationString + "home.html";
		}
	);
}
