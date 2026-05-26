<?php
require_once '../db.php'; 

// Check if $pdo exists
if (!isset($pdo)) {
    die("Error: Database connection ($pdo) not found. Check db.php.");
}

$search = isset($_GET['search']) ? $_GET['search'] : "";
$searchTerm = "%$search%";

// PDO uses ":" named placeholders or "?" positional placeholders
$sql = "SELECT * FROM publication 
        WHERE pub_id LIKE ? OR title LIKE ? OR subtitle LIKE ? 
        OR language_used LIKE ? OR shelf_location LIKE ? 
        OR series LIKE ? OR edition LIKE ?
        ORDER BY pub_id ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Clerk Publication</title>
    <link rel="stylesheet" href="../css/cler_dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>

<header class="topbar">
    <div class="logo-section">
        <h1>Library Clerk Publication</h1>
        <p class="eyebrow">View Publications Only</p>
    </div>
    <form class="d-flex mx-auto" method="GET" style="width: 600px;">
        <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search); ?>">
        <button class="btn search-btn ms-2" type="submit">Search</button>
    </form>
</header>

<nav>
    <a href="clerk_dashboard.php">DASHBOARD</a>
    <a href="reports.php">REPORTS</a>
    <a href="clerk_publication.php" class="active">PUBLICATIONS</a>
</nav>

<main class="dashboard-container">
    <section class="table-section">
        <h2 class="section-title"><i class="bi bi-book-half"></i> Library Publications</h2>
        <div class="table-wrapper" style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Title</th><th>Subtitle</th><th>Category</th><th>Lang</th><th>Shelf</th><th>Pages</th><th>Series</th><th>Edition</th><th>Pub ID</th><th>Date</th><th>Copyright</th>
                    </tr>
                </thead>
               <tbody>
    <?php
    if (count($result) > 0) {
        foreach ($result as $row) {
    ?>
    <tr>
        <td><strong><?php echo htmlspecialchars($row['pub_id']); ?></strong></td>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['subtitle']); ?></td>
        <td><?php echo htmlspecialchars($row['category'] ?? 'Science'); ?></td>
        <td><?php echo htmlspecialchars($row['language_used']); ?></td>
        <td><?php echo htmlspecialchars($row['shelf_location']); ?></td>
        <td><?php echo htmlspecialchars($row['pages']); ?></td>
        <td><?php echo htmlspecialchars($row['series']); ?></td>
        <td><?php echo htmlspecialchars($row['edition']); ?></td>
        <td><?php echo htmlspecialchars($row['publisher_id']); ?></td>
        <td><?php echo htmlspecialchars($row['date_published']); ?></td>
        <td><?php echo htmlspecialchars($row['copyright_date']); ?></td>
    </tr>
    <?php
        }
    } else {
        echo "<tr><td colspan='12' style='text-align:center;'>No publications found.</td></tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>