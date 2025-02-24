document.addEventListener("DOMContentLoaded", function() {
    // Quick Create Button
    let button = document.createElement("button");
    button.id = "quickcreate-btn";
    button.innerText = "+";
    document.body.appendChild(button);

    button.addEventListener("click", function() {
        // Create a modal and form
        let modal = document.createElement("div");
        modal.id = "create-page-modal";
        modal.innerHTML = `
            <div class="modal-content">
                <h2>Create a New Page</h2>
                <form id="create-page-form">
                    <table>
                        <tr>
                            <td><label for="namespace">Namespace:</label></td>
                            <td><input type="text" id="namespace" name="namespace" placeholder="Enter Namespace (optional)"></td>
                        </tr>
                        <tr>
                            <td><label for="pagename">Page Name:</label></td>
                            <td><input type="text" id="pagename" name="pagename" placeholder="Enter Page Name" required></td>
                        </tr>
                    </table>
                    <button type="submit">Create Page</button>
                    <button type="button" id="cancel-button">Cancel</button>
                </form>
                <!-- Info Button (SVG) -->
                <div class="info-button" id="info-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336l24 0 0-64-24 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l48 0c13.3 0 24 10.7 24 24l0 88 8 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-80 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                    </svg>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Info button click handler - Open a new tab with the specified URL
        document.getElementById("info-button").addEventListener("click", function() {
            window.open("https://github.com/Rockyroad1827/dokuwikiplugin/blob/main/README.md", "_blank");
        });

        // Handle form submission
        document.getElementById("create-page-form").addEventListener("submit", function(event) {
            event.preventDefault();
            
            let ns = document.getElementById("namespace").value.trim();
            let name = document.getElementById("pagename").value.trim();

            if (!name) {
                alert("Page name is required!");
                return;
            }

            let page = ns ? `${ns}:${name}` : name;
            page = encodeURIComponent(page.replace(/\s+/g, "_")); // Replace spaces with underscores

            // Create the page with empty content (No check for existence)
            let content = ""; // Empty content for a new page

            fetch(DOKU_BASE + "doku.php", {
                method: "POST",
                body: new URLSearchParams({
                    "id": page,
                    "do": "edit",
                    "content": content // No content, just create the page
                }),
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                }
            }).then(response => {
                // Redirect to the editor page after creation
                window.location.href = DOKU_BASE + "doku.php?id=" + page;
            }).catch(error => {
                console.error('Error creating the page:', error);
            });

            // Close the modal after submission
            modal.remove();
        });

        // Close modal when cancel is clicked
        document.getElementById("cancel-button").addEventListener("click", function() {
            modal.remove();
        });
    });
});
