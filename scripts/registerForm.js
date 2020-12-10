var registerFormContent = `
Email: <input id="emailField" type="text" name="email"><br>
Username: <input id="usernameField" type="text" name="username"><br>
Password: <input id="passwordField" type="password" name="password"><br>
Verify Password: <input id="verifyPasswordField" type="password" name="passwordCheck"><br>
<button id="submit">Submit</button>
`;
function createRegisterForm() {
	$("#startForm").html(registerFormContent);
	$("#startForm").attr("onsubmit", "addUser()");
}

function addUser() {
	event.preventDefault(); // stop page from reloading
	var password = $("#passwordField").val();
	var verifyPassword = $("#verifyPasswordField").val();
	if(password != verifyPassword) {
		alert("Passwords do not match");
		return;
	}
	$.post("server/addUser.php", {
		email: $("#emailField").val(),
		username: $("#usernameField").val(),
		password: $("#passwordField").val(),
		verifyPassword: $("#verifyPasswordField").val()
		}, function(data, status) {
			alert(data);
			console.log(data);
		}
	);
}
