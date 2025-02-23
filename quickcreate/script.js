document.addEventListener("DOMContentLoaded", function () {
    let button = document.getElementById("quickcreate-btn");
    let form = document.getElementById("quickcreate-form");
    let overlay = document.getElementById("quickcreate-overlay");
    let successPopup = document.getElementById("quickcreate-success");
    let createBtn = document.getElementById("qc-create-btn");
    let cancelBtn = document.getElementById("qc-cancel-btn");
    let successCloseBtn = document.getElementById("qc-success-close");
    let dontRedirect = document.getElementById("qc-dont-redirect");

    function showForm() { 
        form.style.display = "block"; 
        overlay.style.display = "block"; 
    }
    
    function hideForm() { 
        form.style.display = "none"; 
        overlay.style.display = "none"; 
    }
    
    function showSuccessPopup() {
        successPopup.style.display = "block";
        setTimeout(() => {
            successPopup.style.display = "none";
        }, 3000);
    }

    button.addEventListener("click", showForm);
    cancelBtn.addEventListener("click", hideForm);
    overlay.addEventListener("click", hideForm);
    successCloseBtn.addEventListener("click", () => successPopup.style.display = "none");

    createBtn.addEventListener("click", function () {
        let namespace = document.getElementById("qc-namespace").value.trim();
        let pagename = document.getElementById("qc-pagename").value.trim();

        if (!pagename) {
            alert("Please enter a page name.");
            return;
        }

        let skipRedirect = dontRedirect.checked;

        let formData = new FormData();
        formData
