//Created by Rayyan Akhlaq - 230074804 -->
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(".search");
    const sortSelect = document.querySelector(".sort");
    const categorySelect = document.querySelector(".category-filter");
    const minPriceInput = document.getElementById("minPrice");
    const maxPriceInput = document.getElementById("maxPrice");
    const productItems = Array.from(document.querySelectorAll(".items"));
    const container = document.querySelector(".products");

    function filterProducts() {
        const searchValue = searchInput.value.toLowerCase();
        const categoryValue = categorySelect.value;
        const minPrice = parseFloat(minPriceInput.value) || 0;
        const maxPrice = parseFloat(maxPriceInput.value) || Infinity;
        const filteredItems = [];

        productItems.forEach((item) => {
            const productName = item.querySelector("h3").textContent.toLowerCase();
            const productCategory = item.getAttribute("data-category");
            const productPrice = parseFloat(item.querySelector("h3:nth-of-type(2)").textContent.replace("£", ""));

            const matchesSearch = productName.includes(searchValue);
            const matchesCategory = categoryValue === "All" || productCategory === categoryValue;
            const matchesPrice = productPrice >= minPrice && productPrice <= maxPrice;

            if (matchesSearch && matchesCategory && matchesPrice) {
                filteredItems.push(item);
            }
        });

        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
        filteredItems.forEach((item) => container.appendChild(item));
    }

    function sortProducts() {
        const sortValue = sortSelect.value;
        const itemsArray = Array.from(productItems);
        const container = document.querySelector(".products");

        itemsArray.sort((a, b) => {
            const nameA = a.querySelector("h3").textContent.toLowerCase();
            const nameB = b.querySelector("h3").textContent.toLowerCase();
            const priceA = parseFloat(a.querySelector("h3:nth-of-type(2)").textContent.replace("£", ""));
            const priceB = parseFloat(b.querySelector("h3:nth-of-type(2)").textContent.replace("£", ""));

            if (sortValue === "sortNameAsc") return nameA.localeCompare(nameB);
            if (sortValue === "sortNameDes") return nameB.localeCompare(nameA);
            if (sortValue === "sortPriceAsc") return priceA - priceB;
            if (sortValue === "sortPriceDes") return priceB - priceA;
        });

        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }
        itemsArray.forEach((item) => container.appendChild(item));
    }

    searchInput.addEventListener("input", filterProducts);
    categorySelect.addEventListener("change", filterProducts);
    sortSelect.addEventListener("change", sortProducts);
    minPriceInput.addEventListener("input", filterProducts);
    maxPriceInput.addEventListener("input", filterProducts);
});