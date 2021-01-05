


// API RND
const getTitles = async (subj, elId) => {
  const res = await fetch(`./api/v1/movies/${subj}.php`)
    .then(resp => resp.json())
    .then(res => {
      console.log(res);
      pushHTML(res, elId); })
    .catch(error => console.log(error));
}
// HTML
const pushHTML = (data, elId) => {
  document.querySelector(elId).innerHTML =
    `
      <div class="json">
        <pre>
          ${JSON.stringify(data)}
        </pre>
      </div>
    `
}
// BUTTONS
document.querySelector('#btn-rnd').addEventListener('click', () => {
  getTitles('rnd', '#res-movies')
})
document.querySelector('#btn-ltst').addEventListener('click', () => {
  getTitles('ltst', '#res-latest')
})

const getmovieForm = document.forms['getmovie'];
getmovieForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const tmdbId = document.querySelector('#tmdbid').value;
  const formData = new FormData();
  formData.append("mid", tmdbId);
  // console.log("you want movie: ",formData);
  getMovieFromTMDB(formData);
})

const getMovieFromTMDB = async (formData) => {
  let reqOpt = {
    method: 'POST',
    body: formData
  }
  const res = await fetch('/api/v1/movies/curl.php', reqOpt)
    .then(resp => resp.json())
    .then(res => gotTMDbData(res))
    .catch(error => console.log(error));
}

const gotTMDbData = ({data}) => {
  console.log(data);
  document.querySelector('#got-tmdb-movie').innerHTML = `
    <div>
    <h3>
      ${data.title}
    </h3>
    <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2${data.poster_path}" alt="poster"/>
    
    </div>
  `;
}