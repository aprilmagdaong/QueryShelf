<?php
session_start();
require '../db.php';
$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt1 = $pdo->prepare("UPDATE user SET user_fname=?, user_lname=?, user_midInit=?, gender=?, email=? WHERE user_id=?");
        $stmt1->execute([$_POST['user_fname'], $_POST['user_lname'], $_POST['user_midInit'], $_POST['gender'], $_POST['email'], $user_id]);

        $stmt2 = $pdo->prepare("UPDATE `user location` SET user_brgy=?, user_municipality=?, user_province=? WHERE add_id=(SELECT add_id FROM user WHERE user_id=?)");
        $stmt2->execute([$_POST['user_brgy'], $_POST['user_municipality'], $_POST['user_province'], $user_id]);

        $stmt3 = $pdo->prepare("UPDATE `library clerk` SET libclerk_username=?, duty_schedule=?, assigned_counter=? WHERE user_id=?");
        $stmt3->execute([$_POST['libclerk_username'], $_POST['duty_schedule'], $_POST['assigned_counter'], $user_id]);

        $pdo->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT u.*, c.*, a.* FROM user u LEFT JOIN `library clerk` c ON u.user_id=c.user_id LEFT JOIN `user location` a ON u.add_id=a.add_id WHERE u.user_id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Library Clerk Profile</title>
    <link rel="stylesheet" href="/QueryShelf/css/clerk_profile.css">
</head>
<body>
<nav class="navbar px-4">
    <div class="logo">QueryShelf</div>

    <div class="nav-right">
        <a href="/QueryShelf/php/clerk_profile.php" class="icon-btn"><img src="/QueryShelf/assets/person-circle.svg"></a>
        <a href="/QueryShelf/php/logout.php" class="icon-btn"><img src="/QueryShelf/assets/box-arrow-right.svg"></a>
    </div>
</nav>

<div class="back-container">
    <a href="javascript:history.back()" class="back-btn">← Back</a>
</div>

<div class="profile-container">
    <div class="header-row">
        <h2>Staff Profile</h2>
        <button class="edit-btn" onclick="document.getElementById('editModal').classList.add('show')">Edit Profile</button>
    </div>

    <?php if ($message): ?>
        <div style="padding: 15px; margin-bottom: 20px; background: #d4edda; color: #155724; border-radius: 10px;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <div class="grid">
        <div class="card">
            <h3>Personal</h3>
            <label>First Name</label><p><?= htmlspecialchars($user['user_fname'] ?? 'N/A') ?></p>
            <label>Last Name</label><p><?= htmlspecialchars($user['user_lname'] ?? 'N/A') ?></p>
            <label>Middle Initial</label><p><?= htmlspecialchars($user['user_midInit'] ?? 'N/A') ?></p>
            <label>Gender</label><p><?= htmlspecialchars($user['gender'] ?? 'N/A') ?></p>
            <label>Email</label><p><?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
        </div>
        <div class="card">
            <h3>Location</h3>
            <label>Barangay</label><p><?= htmlspecialchars($user['user_brgy'] ?? 'N/A') ?></p>
            <label>Municipality</label><p><?= htmlspecialchars($user['user_municipality'] ?? 'N/A') ?></p>
            <label>Province</label><p><?= htmlspecialchars($user['user_province'] ?? 'N/A') ?></p>
        </div>
        <div class="card">
            <h3>Work</h3>
            <label>Username</label><p><?= htmlspecialchars($user['libclerk_username'] ?? 'N/A') ?></p>
            <label>Duty Schedule</label><p><?= htmlspecialchars($user['duty_schedule'] ?? 'N/A') ?></p>
            <label>Assigned Counter</label><p><?= htmlspecialchars($user['assigned_counter'] ?? 'N/A') ?></p>
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <form method="POST">
            <h3>Edit Profile</h3>
            <input type="text" name="user_fname" value="<?= htmlspecialchars($user['user_fname'] ?? '') ?>" placeholder="First Name">
            <input type="text" name="user_lname" value="<?= htmlspecialchars($user['user_lname'] ?? '') ?>" placeholder="Last Name">
            <input type="text" name="user_midInit" value="<?= htmlspecialchars($user['user_midInit'] ?? '') ?>" placeholder="Middle Initial">
            <select name="gender">
                <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="Email">
            <input type="text" name="user_brgy" value="<?= htmlspecialchars($user['user_brgy'] ?? '') ?>" placeholder="Barangay">
            <input type="text" name="user_municipality" value="<?= htmlspecialchars($user['user_municipality'] ?? '') ?>" placeholder="Municipality">
            <input type="text" name="user_province" value="<?= htmlspecialchars($user['user_province'] ?? '') ?>" placeholder="Province">
            <input type="text" name="libclerk_username" value="<?= htmlspecialchars($user['libclerk_username'] ?? '') ?>" placeholder="Username">
            <input type="text" name="duty_schedule" value="<?= htmlspecialchars($user['duty_schedule'] ?? '') ?>" placeholder="Duty Schedule">
            <input type="text" name="assigned_counter" value="<?= htmlspecialchars($user['assigned_counter'] ?? '') ?>" placeholder="Counter">
            <div class="modal-actions">
                <button type="button" class="cancel-btn" onclick="document.getElementById('editModal').classList.remove('show')">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script src="/QueryShelf/js/clerk_profile.js"></script>
</body>
</html>