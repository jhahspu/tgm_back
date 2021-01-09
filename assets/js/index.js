



// DOCUMENT CLICK EVENTS
document.onclick = function(e) {

  if (e.target.id === "refresh-btn") refreshBtnClick() ;

  if (e.target.id === "theme-btn")  switchTheme();
  
  if (e.target.id === "signin-btn") console.log("sign in");

}







/**
 * Check Theme
 */
const checkTheme = () => {
  let isDark;
  if (localStorage.getItem("darkTheme")) {
    isDark = JSON.parse(localStorage.getItem("darkTheme"));
  } else {
    isDark = false;
    localStorage.setItem("darkTheme", JSON.stringify(isDark));
  }
  applyTheme(isDark);
}
/**
 * Theme toggler
 */
const switchTheme = () => {
  let isDark = JSON.parse(localStorage.getItem("darkTheme"));
  isDark = !isDark;
  localStorage.setItem("darkTheme", JSON.stringify(isDark));
  applyTheme(isDark);
}
/**
 * Apply theme
 */
const applyTheme = (isDark) => {
  if (isDark) {
    document.querySelector(".theme-icon-light").classList.remove('hidden');
    document.querySelector(".theme-icon-dark").classList.add('hidden');
    document.body.classList.remove("theme-light");
    document.body.classList.add("theme-dark");

  } else {
    document.querySelector(".theme-icon-light").classList.add('hidden');
    document.querySelector(".theme-icon-dark").classList.remove('hidden');
    document.body.classList.remove("theme-dark");
    document.body.classList.add("theme-light");
  }
}







/**
 * Refresh slides
 */
const refreshBtnClick = async () => {
  const formData = new FormData();
  formData.append("req", "rnd-titles");
  formData.append("genre", "any");
  let reqOpt = {
    method: "POST",
    body: formData
  }
  const res = await fetch("/api/v1/movies/", reqOpt)
    .then(resp => resp.json())
    .then(res => {
      localStorage.setItem("mvs", JSON.stringify(res));
      prepHtml(res);
      createToast(res["message"], "Et Voilà !");
      sliderCheck();
    })
    .catch(error => console.log(error));
}



const prepHtml = (data) => {
  const slider = document.querySelector(".slider");
  slider.innerHTML = "";
  const movies = data["data"];
  movies.forEach(movie => {
    const card = document.createElement('movie-card');
    card.movie = movie;
    slider.appendChild(card);
  });
  
}





/** CUSTOM SLIDER
 * 
 */
const sliderCheck = () => {
  const slider = document.querySelector(".slider");
  const btnNext = document.querySelector("#slide-next");
  const btnPrev = document.querySelector("#slide-prev");

  let sliderCW = slider.clientWidth;
  let sliderSW = slider.scrollWidth;
  let sliderX = 0;
  slideModulo = sliderCW % 225;
  slider.style.transform = `translateX(0px)`;

  btnNext.addEventListener("click", () => {
    ( (sliderX - sliderCW) * -1 < sliderSW ) ? sliderX -= sliderCW - (slideModulo) : null;
    slider.style.transform = `translateX(${sliderX}px)`;
  });

  btnPrev.addEventListener("click", () => {
    (sliderX * -1 > 0) ? sliderX += sliderCW - (slideModulo) : null; 
    slider.style.transform = `translateX(${sliderX}px)`;
  });
}








/**
 * Create Toast
 * @param tc - class name: success / danger
 * @param tm - message
 */
const createToast = (tc, tm) => {
  toast = `
    <div id="toast" class="${tc}">${tm}</div>
  `;
  document.body.innerHTML += toast;
  removeToast(5);
}
/**
 * Remove Toast
 * @param timeout - seconds till dismiss
 */
const removeToast = (timeout) =>{
  setTimeout(() => {
    document.getElementById("toast").remove();
  }, timeout * 1000);
}





/**
 * Window Load
 */
window.addEventListener("load", () => {
  localStorage.getItem("mvs") ? prepHtml(JSON.parse(localStorage.getItem("mvs"))) : null;
  sliderCheck();
  checkTheme();
})

/**
 * Window Resize
 */
window.addEventListener("resize", () => {
  sliderCheck();
})








