// LOGIN
const signInSubmit = document.forms['signin']['signInSubmit'];
signInSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let email = document.getElementById('email').value;
  let pass = document.getElementById('password').value;
  if (email != '' && pass != '') {
    const formattedFormData = {
      email,
      pass
    };
    postData('signin', formattedFormData);
  } else {
    alert("empty sign in fields !");
  }
});


// REGISTER
const registerSubmit = document.forms['register']['registerSubmit'];
registerSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let email = document.getElementById('remail').value;
  let pass = document.getElementById('rpassword1').value;
  let passv = document.getElementById('rpassword2').value;

  if (email != '' && pass != '' && pass === passv) {
    const formattedFormData = {
      email,
      pass
    };
    postData('register', formattedFormData);
  } else {
    alert("register fields wrong !");
  }
});


// HANDLE POST REQUEST FOR SIGN IN AND REGISTER
const postData = async (req, formattedFormData) => {
  let tHeaders = new Headers();
  tHeaders.append("Content-Type", "application/json");
  let bodyRaw = JSON.stringify(formattedFormData);
  let reqOpt = {
    method: 'POST',
    headers: tHeaders,
    body: bodyRaw,
    redirect: 'follow'
  }
  if (req === 'signin'){
    const res = await fetch('/api/v1/users/lgn.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  } else if (req === 'register') {
    const res = await fetch('/api/v1/users/reg.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  }
}
