// ---------- бургер-меню ----------
// 1. находим кнопку и само меню по их id
const burger = document.getElementById("burger");
const nav = document.getElementById("siteNav");

// 2. по клику на кнопку добавляем меню класс "open" или убираем его
burger.addEventListener("click", function () {
  nav.classList.toggle("open");
});
