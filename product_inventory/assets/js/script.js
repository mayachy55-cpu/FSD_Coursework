// Hamburger Menu Toggle
const hamburger = document.getElementById("hamburger");
const navLinks = document.getElementById("navLinks");

if (hamburger && navLinks) {
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("active");
    navLinks.classList.toggle("active");
  });

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
    if (!hamburger.contains(event.target) && !navLinks.contains(event.target)) {
      hamburger.classList.remove("active");
      navLinks.classList.remove("active");
    }
  });

  // Close menu when clicking a nav link
  const navLinksItems = navLinks.querySelectorAll(".nav-link");
  navLinksItems.forEach(link => {
    link.addEventListener("click", function () {
      hamburger.classList.remove("active");
      navLinks.classList.remove("active");
    });
  });
}

// Search functionality
const searchInput = document.getElementById("search");
const resultDiv = document.getElementById("result");
const tableWrapper = document.getElementById("tableWrapper");

if (searchInput && resultDiv && tableWrapper) {
  searchInput.addEventListener("keyup", function () {
    const q = this.value.trim();

    if (q === "") {
      resultDiv.innerHTML = "";
      tableWrapper.style.display = "block";
      return;
    }

    fetch("ajax_search.php?q=" + encodeURIComponent(q))
      .then(res => res.text())
      .then(data => {
        resultDiv.innerHTML = data;
        tableWrapper.style.display = "none";
      });
  });
}
