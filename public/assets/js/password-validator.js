const password = document.getElementById("password");
const confirmPassword = document.getElementById("password_confirm");

const rules = {
  length: document.getElementById("rule-length"),
  upper: document.getElementById("rule-upper"),
  lower: document.getElementById("rule-lower"),
  special: document.getElementById("rule-special"),
  match: document.getElementById("rule-match"),
  number: document.getElementById('rule-number'),
};

function validate() {
  const value = password.value;
  const confirm = confirmPassword.value;

  toggle(rules.length, value.length >= 8);
  toggle(rules.upper, /[A-Z]/.test(value));
  toggle(rules.lower, /[a-z]/.test(value));
  toggle(rules.special, /[^A-Za-z0-9]/.test(value));
  toggle(rules.match, value !== "" && value === confirm);
  toggle(rules.number, /[0-9]/.test(value));
}

function toggle(element, valid) {
  element.classList.remove("rule-valid", "rule-invalid");
  element.classList.add(valid ? "rule-valid" : "rule-invalid");
}

password.addEventListener("input", validate);
confirmPassword.addEventListener("input", validate);
