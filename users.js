// LOGIN
const signInSubmit = document.forms['signin']['signInSubmit'];
signInSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let email = document.getElementById('email').value;
  let pass = document.getElementById('password').value;
  let req = 'signin';
  if (email != '' && pass != '') {
    const formattedFormData = {
      email,
      pass,
      req
    };
    postData(req, formattedFormData);
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
  let req = 'register';
  if (email != '' && pass != '' && pass === passv) {
    const formattedFormData = {
      email,
      pass,
      req
    };
    postData(req, formattedFormData);
  } else {
    alert("register fields wrong !");
  }
});


// CHECKTOKEN
const tokenSubmit = document.forms['checktoken']['tokenSubmit'];
tokenSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let token = document.getElementById('token').value;
  if (token != '') {
    const formattedFormData = {
      token
    };
    postData('token', formattedFormData);
  } else {
    alert("token required");
  }
});


// CHANGE PASSWORD
const changePassSubmit = document.forms['changepass']['changePassSubmit'];
changePassSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let token = document.getElementById('ctoken').value;
  let oldpass = document.getElementById('cpassword1').value;
  let newpass = document.getElementById('cpassword2').value;
  let cnewpass = document.getElementById('cpassword3').value;
  if (token != '' && oldpass != '' && newpass == cnewpass) {
    const formattedFormData = {
      token,
      oldpass,
      newpass
    };
    postData('newpass', formattedFormData);
  } else {
    alert("data required");
  }
});


// REMOVE USER
const remUserSubmit = document.forms['remuser']['remUserSubmit'];
remUserSubmit.addEventListener('click', function(event) {
  event.preventDefault();
  let token = document.getElementById('mtoken').value;
  let pass = document.getElementById('mpassword').value;
  if (token != '' && pass != '') {
    const formattedFormData = {
      token,
      pass
    };
    postData('remuser', formattedFormData);
  } else {
    alert("data required");
  }
});


// UPLOAD USER PROFILE PIC
const avatarForm = document.forms['avatarform'];
avatarForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const files = document.querySelector('#avatarfile').files;
  const formData = new FormData();
  for (let i=0; i<files.length; i++){
    let file = files[i];
    formData.append('files[]', file);
  }
  postAvatar(formData);
})


// POST AVATAR
const postAvatar = async(formattedFormData) => {
  let reqOpt = {
    method: 'POST',
    body: formattedFormData
  }
  const res = await fetch('/api/v1/users/upi.php', reqOpt)
    .then(resp => resp.json())
    .then(res => console.log(res))
    .catch(error => console.log(error));
}


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
    const res = await fetch('/api/v1/users/lgn.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  } else if (req === 'token') {
    const res = await fetch('/api/v1/users/tkn.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  } else if (req === 'newpass') {
    const res = await fetch('/api/v1/users/pass.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  } else if (req === 'remuser') {
    const res = await fetch('/api/v1/users/rem.php', reqOpt)
      .then(resp => resp.json())
      .then(res => console.log(res))
      .catch(error => console.log('error', error));
  }
}