<?php
session_start();
require '../db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: /QueryShelf/php/login.php");
    exit();
}

/* ================= SEARCH ================= */
$search = isset($_GET['search']) ? $_GET['search'] : "";
$searchTerm = "%$search%";

/* ================= FETCH ================= */
$sql = "SELECT borrows.*, fines.amount AS fines
        FROM borrows
        LEFT JOIN fines ON borrows.borrow_id = fines.borrow_id
        WHERE borrows.borrow_id LIKE ? 
        OR user_id LIKE ? 
        OR accession_id LIKE ? 
        OR date_borrowed LIKE ? 
        OR date_returned LIKE ? 
        OR borrowingperiod_id LIKE ?
        ORDER BY borrows.borrow_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $searchTerm,
    $searchTerm,
    $searchTerm,
    $searchTerm,
    $searchTerm,
    $searchTerm
]);

$results = $stmt->fetchAll();


/* ================= ADD ================= */
if (isset($_POST['add_transaction'])) {

    $stmt = $pdo->prepare("
        INSERT INTO borrows 
        (user_id, accession_id, date_borrowed, date_returned, borrowingperiod_id)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['user_id'],
        $_POST['accession_id'],
        $_POST['date_borrowed'],
        $_POST['date_returned'],
        $_POST['borrowingperiod_id']
    ]);

    header("Location: clerk_dashboard.php");
    exit();
}


/* ================= UPDATE ================= */
if (isset($_POST['update_transaction'])) {

    $stmt = $pdo->prepare("
        UPDATE borrows 
        SET user_id=?, accession_id=?, date_borrowed=?, date_returned=?, borrowingperiod_id=?
        WHERE borrow_id=?
    ");

    $stmt->execute([
        $_POST['user_id'],
        $_POST['accession_id'],
        $_POST['date_borrowed'],
        $_POST['date_returned'],
        $_POST['borrowingperiod_id'],
        $_POST['borrow_id']
    ]);

    header("Location: clerk_dashboard.php");
    exit();
}


/* ================= DELETE (FIXED) ================= */
if (isset($_POST['delete_transaction']) && !empty($_POST['borrow_id'])) {

    $id = (int) $_POST['borrow_id'];

    $stmt = $pdo->prepare("
        UPDATE borrows 
        SET is_deleted = 1 
        WHERE borrow_id = ?
    ");

    $stmt->execute([$id]);

    header("Location: clerk_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Library Clerk Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/QueryShelf/css/cler_dashboard.css">

<style>
tr.selectable { cursor: pointer; }
tr.selected td { background-color: #dbeafe !important; }
</style>
</head>

<body>

<nav class="navbar px-4">
    <div class="logo">QueryShelf</div>

    <form class="d-flex mx-auto" method="GET" style="width: 400px;">
        <input type="text" name="search" class="form-control" placeholder="Search date of transactions..." value="<?= htmlspecialchars($search); ?>">
        <button class="btn search-btn ms-2" type="submit">Search</button>
    </form>

    <div class="nav-right">
        <a href="/QueryShelf/php/clerk_dashboard.php" class="icon-btn"><img src="/QueryShelf/assets/house.svg"></a>
        <a href="/QueryShelf/php/clerk_profile.php" class="icon-btn"><img src="/QueryShelf/assets/person-circle.svg"></a>
        <a href="/QueryShelf/php/logout.php" class="icon-btn" onclick="confirmLogout(event)">
            <img src="/QueryShelf/assets/box-arrow-right.svg">
        </a>
    </div>
</nav>

<div class="button-container">
    <a href="/QueryShelf/php/reports.php" class="btn">REPORTS</a>
    <a href="/QueryShelf/php/clerk_publication.php" class="btn">PUBLICATIONS</a>
</div>

<div class="transactions-container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Recent Transactions</h2>

        <div class="header-action d-flex gap-2">
            <button class="btn search-btn" id="addBtn">+ Add Transaction</button>
            <button class="btn btn-primary" id="updateBtnTop">Update</button>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Borrow ID</th>
                <th>User ID</th>
                <th>Accession ID</th>
                <th>Date Borrowed</th>
                <th>Date Returned</th>
                <th>Period ID</th>
                <th>Fines</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($results as $row): ?>
                <tr class="selectable"
                    data-id="<?= $row['borrow_id']; ?>"
                    data-user="<?= $row['user_id']; ?>"
                    data-accession="<?= $row['accession_id']; ?>"
                    data-borrowed="<?= $row['date_borrowed']; ?>"
                    data-returned="<?= $row['date_returned']; ?>"
                    data-period="<?= $row['borrowingperiod_id']; ?>">

                    <td><?= $row['borrow_id']; ?></td>
                    <td><?= $row['user_id']; ?></td>
                    <td><?= $row['accession_id']; ?></td>
                    <td><?= $row['date_borrowed']; ?></td>
                    <td><?= $row['date_returned']; ?></td>
                    <td><?= $row['borrowingperiod_id']; ?></td>

                    <td>
                        <?= ($row['fines'] !== null && $row['fines'] > 0)
                            ? '₱' . number_format($row['fines'], 2)
                            : '—'; ?>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST">

        <div class="modal-header">
          <h5 class="modal-title">Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="borrow_id" id="borrow_id">

          <label class="form-label">User ID</label>
          <input class="form-control mb-2" name="user_id" id="user_id">

          <label class="form-label">Accession ID</label>
          <input class="form-control mb-2" name="accession_id" id="accession_id">

          <label class="form-label">Date Borrowed</label>
          <input class="form-control mb-2" type="date" name="date_borrowed" id="date_borrowed">

          <label class="form-label">Date Returned</label>
          <input class="form-control mb-2" type="date" name="date_returned" id="date_returned">

          <label class="form-label">Borrowing Period ID</label>
          <input class="form-control mb-2" name="borrowingperiod_id" id="borrowingperiod_id">

        </div>

        <div class="modal-footer">
          <button class="btn btn-success" name="update_transaction">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="POST">

        <div class="modal-header">
          <h5 class="modal-title text-danger">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          Are you sure you want to delete this transaction?
          <input type="hidden" name="borrow_id" id="delete_id">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Cancel
          </button>

          <button type="submit" class="btn btn-danger" name="delete_transaction">
              Delete
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="/QueryShelf/js/clerk_dashboard.js"></script>

</body>
</html>