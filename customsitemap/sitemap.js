document.addEventListener("DOMContentLoaded", function () {
    const sitemapContainer = document.getElementById("custom-sitemap");

    if (!sitemapContainer || !window.sitemapData) return;

    // Create Search Bar
    const searchInput = document.createElement("input");
    searchInput.setAttribute("type", "text");
    searchInput.setAttribute("placeholder", "Search...");
    searchInput.id = "sitemap-search";
    sitemapContainer.appendChild(searchInput);

    const namespacesWrapper = document.createElement("div");
    namespacesWrapper.id = "namespaces-wrapper";
    sitemapContainer.appendChild(namespacesWrapper);

    function renderSitemap(filter = "") {
        namespacesWrapper.innerHTML = "";
        sitemapData.forEach(ns => {
            if (filter && !ns.name.includes(filter)) return;

            const nsBox = document.createElement("div");
            nsBox.classList.add("namespace-box");
            nsBox.innerHTML = `<strong>${ns.name}</strong>`;
            namespacesWrapper.appendChild(nsBox);

            const pageList = document.createElement("div");
            pageList.classList.add("page-list");
            nsBox.appendChild(pageList);

            nsBox.addEventListener("click", function () {
                pageList.innerHTML = ns.pages.map(page => `<div class="page-item">${page}</div>`).join("");
                pageList.classList.toggle("visible");
            });
        });
    }

    searchInput.addEventListener("input", function () {
        renderSitemap(this.value);
    });

    renderSitemap();
});
