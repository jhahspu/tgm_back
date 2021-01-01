


// API RND
const getTitles = async (subj) => {
  // const res = await fetch(`./api/v1/movies/rnd.php?genre=war`);
  const res = await fetch(`./api/v1/movies/${subj}.php`);
  const json = await res.json();
  // console.log(json);
  return(json);
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
document.querySelector('#btn-rnd').addEventListener('click', () => { getTitles('rnd').then(res => pushHTML(res, '#res-movies') ) })
document.querySelector('#btn-ltst').addEventListener('click', () => { getTitles('ltst').then(res => pushHTML(res, '#res-latest') ) })
