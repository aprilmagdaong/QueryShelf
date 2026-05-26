document.addEventListener("DOMContentLoaded", function () {

    let selectedRow = null;

    document.querySelectorAll(".selectable").forEach(row => {
        row.addEventListener("click", function () {
            document.querySelectorAll(".selectable").forEach(r => r.classList.remove("selected"));
            this.classList.add("selected");
            selectedRow = this;
        });
    });

    document.getElementById("addBtn").addEventListener("click", function () {
        document.getElementById("borrow_id").value = "";
        document.getElementById("user_id").value = "";
        document.getElementById("accession_id").value = "";
        document.getElementById("date_borrowed").value = "";
        document.getElementById("date_returned").value = "";
        document.getElementById("borrowingperiod_id").value = "";

        new bootstrap.Modal(document.getElementById("editModal")).show();
    });

    document.getElementById("updateBtnTop").addEventListener("click", function () {
        if (!selectedRow) return alert("Select row first");

        document.getElementById("borrow_id").value = selectedRow.dataset.id;
        document.getElementById("user_id").value = selectedRow.dataset.user;
        document.getElementById("accession_id").value = selectedRow.dataset.accession;
        document.getElementById("date_borrowed").value = selectedRow.dataset.borrowed;
        document.getElementById("date_returned").value = selectedRow.dataset.returned;
        document.getElementById("borrowingperiod_id").value = selectedRow.dataset.period;

        new bootstrap.Modal(document.getElementById("editModal")).show();
    });

    document.getElementById("deleteBtnTop").addEventListener("click", function () {

        if (!selectedRow) {
            alert("Select row first");
            return;
        }

        let id = selectedRow.dataset.id;

        document.getElementById("delete_id").value = id;

        new bootstrap.Modal(document.getElementById("deleteModal")).show();
    });

});

function confirmLogout(event) {
    event.preventDefault();

    let confirmAction = confirm("Are you sure you want to log out?");

    if (confirmAction) {
        window.location.href = "/QueryShelf/php/logout.php";
    }
}
