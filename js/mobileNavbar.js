const hamburgerMenu = document.getElementById("hamburgerMenu");
const mobileNavLinks = document.getElementById("mobileNavLinks");
const mobileCategories = document.getElementById("mobileCategories");
const mobileDropdownContent = document.getElementById("mobileDropdownContent");

hamburgerMenu.addEventListener("click", () => {
  mobileNavLinks.classList.toggle("active");
});

mobileCategories.addEventListener("click", () => {
  mobileDropdownContent.style.display = mobileDropdownContent.style.display === "block" ? "none" : "block";
});
