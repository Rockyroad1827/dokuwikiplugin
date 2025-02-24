document.addEventListener("DOMContentLoaded", function() {
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
            </div>
        `;
        document.body.appendChild(modal);

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
