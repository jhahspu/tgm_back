// DOCUMENT CLICK EVENTS
document.onclick = function(e) {

  (e.target.id === 'refresh-btn') ? refreshBtnClick() :  null;
  
  (e.target.id === 'signin-btn') ? console.log('signin button hit') :  null;




}



const refreshBtnClick = async () => {
  const formData = new FormData();
  formData.append("req", "rnd-titles");
  formData.append("genre", "any");
  let reqOpt = {
    method: 'POST',
    body: formData
  }
  const res = await fetch('/api/v1/movies/', reqOpt)
    .then(resp => resp.json())
    .then(res => prepHtml(res))
    .catch(error => console.log(error));
}

const prepHtml = (data) => {
  createToast(data['message']);
  const movies = data['data'];
  movies.forEach(movie => {
    document.getElementById("rnd-titles").innerHTML += `
      <div class="card">
      <img src="./p/${movie.poster}.webp" alt="${movie.title}"
      <h3>${movie.title}</h3>
      </div>
    `;
  });
}




// TOAST
// CREATE
const createToast = (message) => {
  toast = `
    <div id="toast" class="${message}">${message}</div>
  `;
  document.body.innerHTML += toast;
  removeToast(5);
}
// REMOVE
const removeToast = (timeout) =>{
  setTimeout(() => {
    document.getElementById("toast").remove();
  }, timeout * 1000);
}















