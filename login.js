var email = document.forms['form']['email'];
var password = document.forms['form']['password'];

var email_error = document.getElementById('email_error');
var pass_error = document.getElementById('pass_error');
document.getElementById('loginButton').addEventListener('click', validated);

email.addEventListener('input', email_Verify);
password.addEventListener('input', pass_Verify);

function validated(event) {
    if (email.value.length < 12 || !isValidEmail(email.value)) {
        email.style.border = "1px solid red";
        email_error.style.display = "block";
        email.focus();
        event.preventDefault();
        return false;
    } else {
        email.style.border = "1px solid silver";
        email_error.style.display = "none";
    }

    if (password.value.length < 12) {
        password.style.border = "1px solid red";
        pass_error.style.display = "block";
        password.focus();
        event.preventDefault();
        return false;
    } else {
        password.style.border = "1px solid silver";
        pass_error.style.display = "none";
    }
}

function email_Verify(event) {
    if (isValidEmail(email.value) || email.value.length >= 10) {
        email.style.border = "1px solid silver";
        email_error.style.display = "none";
        return true;
    }
}

function pass_Verify(event) {
    if (password.value.length >= 12) {
        password.style.border = "1px solid silver";
        pass_error.style.display = "none";
        return true;
    }
}
function isValidEmail(email) {
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(email);
}
