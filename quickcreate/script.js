document.addEventListener("DOMContentLoaded", function() {
    let button = document.createElement("button");
    button.id = "quickcreate-btn";
    button.innerText = "+";
    document.body.appendChild(button);

    button.addEventListener("click", function() {
        let ns = prompt("Enter namespace (leave empty for root):", "").trim();
        let name = prompt("Enter page name:", "").trim();

        if (!name) {
            alert("Page name is required!");
            return;
        }

        let page = ns ? `${ns}:${name}` : name;
        page = encodeURIComponent(page.replace(/\s+/g, "_")); // Replace spaces with underscores

        window.location.href = DOKU_BASE + "doku.php?id=" + page;
    });
});
