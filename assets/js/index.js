import './movie-card.js';
import { CreateToast } from './toast.js';


const overlay = document.querySelector("#overlay");


// DOCUMENT CLICK EVENTS
document.onclick = function(e) {

  if (e.target.id === "refresh-btn") refreshBtnClick() ;

  if (e.target.id === "theme-btn")  switchTheme();
  
  if (e.target.id === "signin-btn") console.log("sign in");

  if (e.target.classList[0] === "btn-trailer") {
    showTrailer(e.target.dataset['trailers']);
    console.log(e.target.classList[0]);
    overlay.classList.toggle("active");
  }

  if (e.target.id === 'overlay' || e.target.id === 'ovcl') {
    overlay.classList.toggle('active');
    overlay.innerHTML = '';
  }
  
  
}


const doSomething = (e, el) => {
  console.log(e);
  console.log(el);

}


/**
 * Show Trailer
 */
const showTrailer = (t) => {
  // TODO: Check Cookie User Agreement
  // let ytId = t.split(' ')[0];
  // overlay.innerHTML +=  
  //   `<div class="overlay-trailer">
  //     <div class="overlay-close" id="ovcl">
  //         <svg class="svg-icon" viewBox="0 0 20 20">
  //           <path fill="none" d="M12.71,7.291c-0.15-0.15-0.393-0.15-0.542,0L10,9.458L7.833,7.291c-0.15-0.15-0.392-0.15-0.542,0c-0.149,0.149-0.149,0.392,0,0.541L9.458,10l-2.168,2.167c-0.149,0.15-0.149,0.393,0,0.542c0.15,0.149,0.392,0.149,0.542,0L10,10.542l2.168,2.167c0.149,0.149,0.392,0.149,0.542,0c0.148-0.149,0.148-0.392,0-0.542L10.542,10l2.168-2.168C12.858,7.683,12.858,7.44,12.71,7.291z M10,1.188c-4.867,0-8.812,3.946-8.812,8.812c0,4.867,3.945,8.812,8.812,8.812s8.812-3.945,8.812-8.812C18.812,5.133,14.867,1.188,10,1.188z M10,18.046c-4.444,0-8.046-3.603-8.046-8.046c0-4.444,3.603-8.046,8.046-8.046c4.443,0,8.046,3.602,8.046,8.046C18.046,14.443,14.443,18.046,10,18.046z"></path>
  //         </svg>
  //     </div>
  //     <div class="iframe-container">
  //       <iframe src="https://www.youtube-nocookie.com/embed/${ytId}"></iframe>
  //     </div>
  //   </div>`;
  // overlay.classList.add('active');
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
      // console.log(res);
      if (res["status"] >= 400) {
        CreateToast(res["status"], res["message"]);
      } else {
        CreateToast(res["status"], res["message"]);
        localStorage.setItem("mvs", JSON.stringify(res));
        prepHtml(res);
        sliderCheck();
      }
    })
    .catch(error => console.log(error));
}

/**
 * Add random titles to slider
 * @param {object} data 
 */
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
  const slideModulo = sliderCW % 225;
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








