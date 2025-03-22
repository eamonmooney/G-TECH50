const hamburgerMenu = document.getElementById("hamburgerMenu");
const mobileNavLinks = document.getElementById("mobileNavLinks");
hamburgerMenu.addEventListener("click", () => {
  mobileNavLinks.classList.toggle("active");
});

function updateDashboard() {
  const data = fetchDashboardData(); // Replace this with an actual fetch call to your backend
  document.getElementById("totalSales").textContent = data.totalSales;
  document.getElementById("activeUsers").textContent = data.activeUsers;
  document.getElementById("pendingTickets").textContent = data.pendingTickets;
  document.getElementById("stockItems").textContent = data.stockItems;
}

document.addEventListener("DOMContentLoaded", updateDashboard);
