function register () {
  // (A) PASSWORD CHECK
  let pass = document.getElementById("reg-pass").value,
      cpass = document.getElementById("reg-cpass").value;
  if (pass!=cpass) {
    cb.modal("Opps", "Passwords do not match!");
    return false;
  }

  // (B) PASSWORD STRENGTH
  if (!cb.checker(pass)) {
    cb.modal("Opps", "Password must be at least 8 characters alphanumeric.");
    return false;
  }

  // (C) CALL API
  cb.api({
    mod : "session", req : "register",
    data : {
      name : document.getElementById("reg-name").value,
      email : document.getElementById("reg-email").value,
      password : pass
    },
    passmsg : false,
    onpass : () => {
      // @TODO - REDIRECT TO WELCOME PAGE OR SOMEWHERE ELSE
      location.href = cbhost.base;
    }
  });
  return false;
}
