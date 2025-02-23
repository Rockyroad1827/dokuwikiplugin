document.addEventListener("DOMContentLoaded", function() {
    // Create Floating Button
    let quickNewPageBtn = document.createElement("button");
    quickNewPageBtn.id = "quickNewPageBtn";
    quickNewPageBtn.innerHTML = "+";
    document.body.appendChild(quickNewPageBtn);

    // Create Modal
    let modal = document.createElement("div");
    modal.id = "quickNewPageModal";
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Create New Page</h3>
            <input type="text" id="namespace" placeholder="Namespace (optional)">
            <input type="text" id="pagename" placeholder="Page Name">
            <button id="createPage">Create</button>
        </div>
    `;
    document.body.appendChild(modal);

    // Event Listeners
    quickNewPageBtn.addEventListener("click", function() {
        modal.classList.add("show");
    });

    modal.querySelector(".close").addEventListener("click", function() {
        modal.classList.remove("show");
    });

    document.getElementById("createPage").addEventListener("click", function() {
        let namespace = document.getElementById("namespace").value.trim();
        let pagename = document.getElementById("pagename").value.trim();

        if (pagename === "") {
            alert("Page name is required!");
            return;
        }

        let pagePath = namespace ? namespace + ":" + pagename : pagename;
        window.location.href = DOKU_BASE + "doku.php?id=" + encodeURIComponent(pagePath) + "&do=edit";
    });
});
