const emailFeedback = document.getElementsByClassName("input__message")[0];
const agreementFeedback = document.getElementsByClassName("agreement__message")[0];
const emailInput = document.getElementsByClassName("form__input")[0];
const submitButton = document.getElementsByClassName("form__submit")[0];
const agreementCheckbox = document.getElementById("agreement");

emailFeedback.textContent = "";
agreementCheckbox.checked = false;
submitButton.disabled = true;
let emailValid = false;

function validateEmail(email) {
    const regex =
        /[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    return regex.test(email);
}

function validate() {

    if (emailInput.value === "") {
        emailFeedback.textContent = "Email address is required";
    } else {
        if (validateEmail(emailInput.value)) {
            if (/(\.co$)/.test(emailInput.value)) {
                emailFeedback.textContent =
                    "We are not accepting subscriptions from Colombia emails";
            } else {
                emailFeedback.textContent = "";
                emailValid = true;
                validateCheckbox()
                return
            }
        } else {
            emailFeedback.textContent = "Please provide a valid e-mail address";
        }
    }
    emailValid = false;
}

function validateCheckbox() {
    if (agreementCheckbox.checked && emailValid) {
        agreementFeedback.textContent = "";
        submitButton.disabled = false;
    } else if (!agreementCheckbox.checked) {
        agreementFeedback.textContent = "You must accept the terms and conditions";
    } else if (!emailValid) {
        agreementFeedback.textContent = "";
        if (emailFeedback.textContent === '') {
            emailFeedback.textContent = "Email address is required";
        }
    }
}

emailInput.addEventListener("keyup", validate);
agreementCheckbox.addEventListener("change", validateCheckbox);