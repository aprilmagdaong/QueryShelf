<?php
require_once '../db.php';

/* ==============================
   FETCH CLERKS (DROPDOWN)
============================== */
$clerks = $pdo->query("SELECT libclerk_id FROM `library clerk`")
              ->fetchAll(PDO::FETCH_ASSOC);

/* ==============================
   HANDLE INSERT REPORT
============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_report'])) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reports (report_id, libclerk_id, report_type, date_generated)
            VALUES (?, ?, ?, NOW())
        ");

        $stmt->execute([
            $_POST['report_id'],
            $_POST['libclerk_id'],
            $_POST['report_type']
        ]);

        header("Location: reports.php?success=1");
        exit();

    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}

/* ==============================
   HANDLE DELETE REPORT
============================== */
if (isset($_POST['delete_report'])) {

    try {
        $stmt = $pdo->prepare("DELETE FROM reports WHERE report_id = ?");
        $stmt->execute([$_POST['report_id']]);

        header("Location: reports.php?deleted=1");
        exit();

    } catch (PDOException $e) {
        $error = "Delete Error: " . $e->getMessage();
    }
}

/* ==============================
   FETCH REPORTS
============================== */
$reports = $pdo->query("SELECT * FROM reports ORDER BY date_generated DESC")
               ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Clerk Reports</title>
    <link rel="stylesheet" href="../css/reports.css">
</head>

<body>

<header class="topbar">
    <div class="logo-section">
        <h1>Library Clerk Reports</h1>
    </div>
</header>

<!-- SUCCESS MESSAGE -->
<?php if (isset($_GET['success'])): ?>
    <div style="margin:10px;padding:10px;background:#d4edda;color:#155724;border-radius:8px;">
        Report added successfully!
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div style="margin:10px;padding:10px;background:#f8d7da;color:#721c24;border-radius:8px;">
        Report deleted successfully!
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="margin:10px;padding:10px;background:#f8d7da;color:#721c24;border-radius:8px;">
        <?= $error ?>
    </div>
<?php endif; ?>

<div class="dashboard-wrapper">

    <div class="top-navbar">
        <a href="/QueryShelf/php/clerk_dashboard.php" class="nav-btn">DASHBOARD</a>
        <a href="/QueryShelf/php/reports.php" class="nav-btn active">REPORTS</a>
        <a href="/QueryShelf/php/clerk_publication.php" class="nav-btn">PUBLICATIONS</a>
    </div>

    <div class="main-card">

        <div class="card-header-row">
            <h1 class="page-title">Available Reports</h1>
            <button class="add-btn" id="openModalBtn">+ Add Report</button>
        </div>

        <div class="table-responsive">
            <table class="dashboard-table">

                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Clerk ID</th>
                        <th>Report Type</th>
                        <th>Date Generated</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['report_id']) ?></td>
                            <td><?= htmlspecialchars($r['libclerk_id']) ?></td>
                            <td><?= htmlspecialchars($r['report_type']) ?></td>
                            <td><?= htmlspecialchars($r['date_generated']) ?></td>

                            <!-- DELETE BUTTON -->
                            <td>
                                <form method="POST" onsubmit="return confirm('Delete this report?');">
                                    <input type="hidden" name="report_id" value="<?= htmlspecialchars($r['report_id']) ?>">
                                    <button type="submit" name="delete_report"
                                            style="background:#dc3545;color:white;border:none;
                                                   padding:6px 12px;border-radius:6px;cursor:pointer;">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<!-- MODAL -->
<div id="reportModal" class="modal">
    <div class="modal-content">

        <div class="modal-header">Add New Report</div>

        <form method="POST">
            <input type="hidden" name="add_report" value="1">

            <div class="form-group">
                <label>Report ID</label>
                <input type="text" name="report_id" required>
            </div>

            <div class="form-group">
                <label>Clerk ID</label>
                <select name="libclerk_id" required>
                    <option value="">-- Select Clerk --</option>
                    <?php foreach ($clerks as $c): ?>
                        <option value="<?= htmlspecialchars($c['libclerk_id']) ?>">
                            <?= htmlspecialchars($c['libclerk_id']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Report Type</label>
                <input type="text" name="report_type" required>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" id="closeModalBtn">Cancel</button>
                <button type="submit" class="btn-save">Save Report</button>
            </div>

        </form>
    </div>
</div>

<script src="/QueryShelf/js/reports.js"></script>

</body>
</html>