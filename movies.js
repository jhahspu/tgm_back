


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
  const gmTkn = document.querySelector('#gmtoken').value;
  const tmdbId = document.querySelector('#tmdbid').value;
  const formData = new FormData();
  if (gmTkn != '' && tmdbId !='') {
    formData.append("mid", tmdbId);
    formData.append("tkn", gmTkn);
    getMovieFromTMDB(formData);
  } else {
    alert('Token & tMDb reuqired');
  }
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

const gotTMDbData = (data) => {
  console.log(data['status']);
  console.log(data['message']);
  if (data['data']) {
    // let movie = {data};
    console.log(data['data']);
    // console.log(movie);
    document.querySelector('#got-tmdb-movie').innerHTML = `
      <div>
      <h3>
        ${data['data']['title']}
      </h3>
      <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2${data['data']['poster_path']}" alt="poster"/>
      
      </div>
    `;
  }
  
}