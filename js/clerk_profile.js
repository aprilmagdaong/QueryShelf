document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("editModal");
    const editBtn = document.querySelector(".edit-btn");
    const cancelBtn = document.querySelector(".cancel-btn");

    // Open modal
    if (editBtn) {
        editBtn.addEventListener("click", () => {
            modal.classList.add("show");
        });
    }

    // Close modal
    if (cancelBtn) {
        cancelBtn.addEventListener("click", () => {
            modal.classList.remove("show");
        });
    }

    // Close when clicking outside the box
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.classList.remove("show");
        }
    });
});