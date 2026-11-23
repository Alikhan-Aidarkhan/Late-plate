// ---------- burger menu ----------
// 1. find the button and the nav by their id
const burger = document.getElementById("burger");
const nav = document.getElementById("siteNav");

// 2. on each click, add the class "open" to the nav, or remove it
burger.addEventListener("click", function () {
  nav.classList.toggle("open");
});


// ---------- menu category filter ----------
// this page may not exist on every page, so check first
const filterBar = document.getElementById("filterBar");

if (filterBar) {
  // querySelectorAll gives us a list of every matching element
  const pills = document.querySelectorAll(".pill");
  const items = document.querySelectorAll(".menu-item");
  const headings = document.querySelectorAll(".menu-list h2");

  pills.forEach(function (pill) {
    pill.addEventListener("click", function () {
      // data-category="burgers" in the HTML is read as pill.dataset.category
      const wanted = pill.dataset.category;

      // move the "on" highlight to the pill that was clicked
      pills.forEach(function (p) {
        p.classList.remove("on");
      });
      pill.classList.add("on");

      // show an item when "All" is picked, or when its category matches
      items.forEach(function (item) {
        item.hidden = !(wanted === "all" || item.dataset.category === wanted);
      });

      // hide the headings of the categories we are not showing
      headings.forEach(function (h) {
        h.hidden = !(wanted === "all" || h.id === wanted);
      });
    });
  });
}


// ---------- gallery: filter + lightbox ----------
const photoGrid = document.getElementById("photoGrid");

if (photoGrid) {
  const galleryPills = document.querySelectorAll("#galleryFilter .pill");
  const tiles = document.querySelectorAll(".tile");

  // --- filter, same idea as the menu page ---
  galleryPills.forEach(function (pill) {
    pill.addEventListener("click", function () {
      const wanted = pill.dataset.category;

      galleryPills.forEach(function (p) {
        p.classList.remove("on");
      });
      pill.classList.add("on");

      tiles.forEach(function (tile) {
        tile.hidden = !(wanted === "all" || tile.dataset.category === wanted);
      });
    });
  });

  // --- lightbox ---
  const lightbox = document.getElementById("lightbox");
  const lbImage = document.getElementById("lbImage");
  const lbCaption = document.getElementById("lbCaption");

  // which photo is on screen right now
  let current = 0;

  // the tiles the viewer can actually see, so arrows skip filtered-out ones
  function visibleTiles() {
    return Array.from(tiles).filter(function (t) {
      return !t.hidden;
    });
  }

  function show(index) {
    const list = visibleTiles();
    if (list.length === 0) {
      return;
    }
    // wrap around at both ends
    if (index < 0) {
      index = list.length - 1;
    }
    if (index >= list.length) {
      index = 0;
    }
    current = index;

    const tile = list[index];
    lbImage.src = tile.dataset.full;
    lbImage.alt = tile.dataset.caption;
    lbCaption.textContent = tile.dataset.caption;
    lightbox.hidden = false;
  }

  function close() {
    lightbox.hidden = true;
  }

  tiles.forEach(function (tile) {
    tile.addEventListener("click", function () {
      show(visibleTiles().indexOf(tile));
    });
  });

  document.getElementById("lbClose").addEventListener("click", close);

  document.getElementById("lbPrev").addEventListener("click", function () {
    show(current - 1);
  });

  document.getElementById("lbNext").addEventListener("click", function () {
    show(current + 1);
  });

  // a click on the dark background closes it, a click on the photo does not
  lightbox.addEventListener("click", function (event) {
    if (event.target === lightbox) {
      close();
    }
  });

  // keyboard: Escape closes, arrows step through
  document.addEventListener("keydown", function (event) {
    if (lightbox.hidden) {
      return;
    }
    if (event.key === "Escape") {
      close();
    }
    if (event.key === "ArrowLeft") {
      show(current - 1);
    }
    if (event.key === "ArrowRight") {
      show(current + 1);
    }
  });
}
