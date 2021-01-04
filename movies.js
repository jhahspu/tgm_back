


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
