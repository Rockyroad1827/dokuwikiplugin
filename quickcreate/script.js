document.addEventListener("DOMContentLoaded", function () {
    let button = document.getElementById("quickcreate-btn");
    let form = document.getElementById("quickcreate-form");
    let overlay = document.getElementById("quickcreate-overlay");
    let createBtn = document.getElementById("qc-create-btn");
    let cancelBtn = document.getElementById("qc-cancel-btn");
    let dontRedirect = document.getElementById("qc-dont-redirect");

    function showForm() {
        form.style.display = "block";
        overlay.style.display = "block";
    }

    function hideForm() {
        form.style.display = "none";
        overlay.style.display = "none";
    }

    button.addEventListener("click", showForm);
    cancelBtn.addEventListener("click", hideForm);
    overlay.addEventListener("click", hideForm);

    createBtn.addEventListener("click", function () {
        let namespace = document.getElementById("qc-namespace").value.trim();
        let pagename = document.getElementById("qc-pagename").value.trim();

        if (!pagename) {
            alert("Please enter a page name.");
            return;
        }

        let skipRedirect = dontRedirect.checked;

        if (skipRedirect) {
            fetch(DOKU_BASE + 'lib/exe/ajax.php?call=quickcreate_create_page', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ namespace: namespace, pagename: pagename })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Page created successfully!");
                } else {
                    alert("Error: " + data.error);
                }
                hideForm();
            })
            .catch(error => alert("An error occurred: " + error));
        } else {
            let fullPageName = namespace ? namespace + ":" + pagename : pagename;
            let url = DOKU_BASE + "doku.php?id=" + encodeURIComponent(fullPageName);
            window.location.href = url;
        }
    });
});
