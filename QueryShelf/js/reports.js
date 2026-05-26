const modal = document.getElementById("reportModal");
const openBtn = document.getElementById("openModalBtn");
const closeBtn = document.getElementById("closeModalBtn");

openBtn.addEventListener("click", () => modal.classList.add("show"));
closeBtn.addEventListener("click", () => modal.classList.remove("show"));

window.addEventListener("click", (event) => {
    if (event.target === modal) modal.classList.remove("show");
});