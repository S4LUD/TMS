const mediaQuery = gsap.matchMedia();

window.addEventListener("resize", function () {
  (async () => {
    var backdrop = document.getElementById("backdrop");
    var isLargeScreen = window.matchMedia("(min-width: 640px)").matches;
    var isBackdrop = !backdrop.classList.contains("hidden");

    if (isBackdrop && isLargeScreen) {
      toggleSideMenu();
    }
  })();
});

async function toggleSideMenu() {
  var sideMenu = document.getElementById("sideMenu");
  var backdrop = document.getElementById("backdrop");
  var container = document.getElementById("main-container");

  sideMenu.classList.toggle("hidden");
  backdrop.classList.toggle("hidden");
  container.classList.toggle("overflow-hidden");
}

const toggleFullscreenButton = document.getElementById("toggleFullscreen");

toggleFullscreenButton.addEventListener("click", () => {
  if (document.fullscreenElement) {
    document.exitFullscreen();
  } else {
    document.documentElement.requestFullscreen();
  }
});

function openLogoutModal() {
  document.getElementById("logoutModal").classList.remove("hidden");
}

function closeLogoutModal() {
  document.getElementById("logoutModal").classList.add("hidden");
}
