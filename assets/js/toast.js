/**
 * Create Toast
 * @param tc - class name: success / danger
 * @param tm - message
 */
export function CreateToast (tc, tm) {
  let toastClass = "info";
  if (tc >= 400) toastClass = "danger";   
  const toast = `<div id="toast" class="${toastClass}">${tm}</div>`;
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

