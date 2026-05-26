function resetForm() { document.getElementById("pubForm").reset(); document.getElementById("pub_id").value = ""; }
    function editRow(btn) {
        const row = btn.closest('tr');
        document.getElementById('pub_id').value = row.cells[0].innerText;
        document.getElementById('title').value = row.cells[1].innerText;
        document.getElementById('series').value = row.cells[2].innerText;
        document.getElementById('edition').value = row.cells[3].innerText;
        document.getElementById('pages').value = row.cells[4].innerText;
        document.getElementById('shelf_location').value = row.cells[5].innerText;
    }

    function confirmLogout(event) {
    event.preventDefault();

    let confirmAction = confirm("Are you sure you want to log out?");

    if (confirmAction) {
        window.location.href = "/QueryShelf/php/logout.php";
    }
}