function setFormMessage(formElement, type, message) {
    const messageElement = formElement.querySelector(".form__message");

    messageElement.textContent = message;
    messageElement.classList.remove("form__message--success", "form__message--error");
    messageElement.classList.add(`form_message--${type}`);
}


document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.querySelector('#login');
    const createAccountForm = document.querySelector('#createAccount');

    document.querySelector('#linkCreateAccount').addEventListener("click", e => {
        e.preventDefault();
        loginForm.classList.add("form--hidden");
        createAccountForm.classList.remove("form--hidden");
    });

    document.querySelector('#linkLogin').addEventListener("click", e => {
        e.preventDefault();
        loginForm.classList.remove("form--hidden");
        createAccountForm.classList.add("form--hidden");
    });

    loginForm.addEventListener("submit", e => {
        e.preventDefault();

        setFormMessage(loginForm, "form__message--error", "Invalid username/password combination"/*Error message about incorrect password goes here.*/);
    });

});

document.querySelector("#loginButton").addEventListener("click", e => {
    dvwaRedirectCopy();
});

function dvwaRedirectCopy() {
    window.location.href = "login.php";
}

document.querySelector("#createAccountButton").addEventListener("click", e => {
    window.location.href = "createuser.php";
});

document.querySelector("#createDatabaseButton").addEventListener("click", e => {
    document.querySelector("#hiddenForm").classList.remove("form--hidden");
    //dvwaRedirectCopy();
 });