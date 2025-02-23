document.addEventListener("DOMContentLoaded", function() {
    // Create Floating Button
    let quickNewPageBtn = document.createElement("button");
    quickNewPageBtn.id = "quickNewPageBtn";
    quickNewPageBtn.innerHTML = '<i class="fas fa-plus"></i>';
    document.body.appendChild(quickNewPageBtn);

    // Create Modal
    let modal = document.createElement("div");
    modal.id = "quickNewPageModal";
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Create a New Page</h2>
            <p>Enter a namespace (optional) and a page name:</p>
            <div class="input-group">
                <label for="namespace">Namespace</label>
                <input type="text" id="namespace" placeholder="e.g., documentation">
            </div>
            <div class="input-group">
                <label for="pagename">Page Name *</label>
                <input type="text" id="pagename" placeholder="e.g., installation-guide" required>
            </div>
            <button id="createPage">Create Page</button>
        </div>
    `;
    document.body.appendChild(modal);

    // Open and Close Modal Events
    quickNewPageBtn.addEventListener("click", function() {
        modal.classList.add("show");
    });

    modal.querySelector(".close").addEventListener("click", function() {
        modal.classList.remove("show");
    });

    // Handle Page Creation
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
