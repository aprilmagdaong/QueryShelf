<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

require_once '../db.php'; 

// Fetch publications
$sql = "SELECT p.*, a.first_name, a.last_name, a.middle_initial 
        FROM publication p 
        LEFT JOIN `pub author` pa ON p.pub_id = pa.pub_id
        LEFT JOIN author a ON pa.author_id = a.author_id";
$publications = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Librarian Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/QueryShelf/css/librarian_dashboard.css">
</head>

    

<body>

<nav class="navbar px-4">
    <div class="logo">QueryShelf</div>

    <div class="nav-right">
        <a href="/QueryShelf/php/clerk_profile.php" class="icon-btn">
            <img src="/QueryShelf/assets/person-circle.svg">
        </a>

        <a href="logout.php" class="icon-btn" onclick="confirmLogout(event)">
            <img src="/QueryShelf/assets/box-arrow-right.svg">
        </a>
    </div>
</nav>

<div class="container-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Publication Registry</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pubModal" onclick="resetForm()">+ Add New</button>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr><th>ID</th><th>Title</th><th>Series</th><th>Edition</th><th>Pages</th><th>Shelf</th><th>Author</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($publications as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row['pub_id']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['series']) ?></td>
                <td><?= htmlspecialchars($row['edition']) ?></td>
                <td><?= htmlspecialchars($row['pages']) ?></td>
                <td><?= htmlspecialchars($row['shelf_location']) ?></td>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#pubModal" onclick="editRow(this)">Edit</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="pubModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="process.php" id="pubForm">
            <div class="modal-header"><h5 class="modal-title">Publication Details</h5></div>
            <div class="modal-body">
                <input type="hidden" name="pub_id" id="pub_id">
                <div class="row">
                    <div class="col-md-6 mb-2"><label>Title</label><input type="text" name="title" id="title" class="form-control" required></div>
                    <div class="col-md-6 mb-2"><label>Subtitle</label><input type="text" name="subtitle" id="subtitle" class="form-control"></div>
                    <div class="col-md-4 mb-2"><label>Series</label><input type="text" name="series" id="series" class="form-control"></div>
                    <div class="col-md-4 mb-2"><label>Edition</label><input type="text" name="edition" id="edition" class="form-control"></div>
                    <div class="col-md-4 mb-2"><label>Pages</label><input type="number" name="pages" id="pages" class="form-control"></div>
                    <div class="col-md-6 mb-2"><label>Shelf Location</label><input type="text" name="shelf_location" id="shelf_location" class="form-control"></div>
                    <div class="col-md-6 mb-2"><label>Language</label><input type="text" name="language_used" id="language_used" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Changes</button></div>
        </form>
    </div>
</div>

<script src="/QueryShelf/js/librarian_dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>