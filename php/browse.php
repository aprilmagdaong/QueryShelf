<?php
require '../db.php'; 

$sql = "SELECT 
            pub_id, title, subtitle, series, edition, 
            date_published, shelf_location, language_used, 
            copyright_date, pages, publisher_id 
        FROM publication 
        ORDER BY pub_id ASC";

try {
    $result = $pdo->query($sql);
} catch (PDOException $e) {
    die("SQL Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" href="../css/browse.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<header class="topbar">
    <div class="logo-section">
        <p class="eyebrow">Browse Library Books</p>
        <h1>QueryShelf</h1>
    </div>
    <div class="header-actions">
        <a href="../index.php" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</header>

<main class="container">
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search books by title...">
        <i class="bi bi-search"></i>
    </div>

    <div class="book-grid" id="bookGrid">
        <?php
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="book-card">
            <div class="book-icon">
                <i class="bi bi-book-half"></i>
            </div>
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p class="subtitle"><?php echo htmlspecialchars($row['subtitle']); ?></p>
            <div class="book-info">
                <span><strong>Book ID:</strong> <?php echo htmlspecialchars($row['pub_id']); ?></span>
                <span><strong>Series:</strong> <?php echo htmlspecialchars($row['series']); ?></span>
                <span><strong>Edition:</strong> <?php echo htmlspecialchars($row['edition']); ?></span>
                <span><strong>Date Published:</strong> <?php echo htmlspecialchars($row['date_published']); ?></span>
                <span><strong>Shelf:</strong> <?php echo htmlspecialchars($row['shelf_location']); ?></span>
                <span><strong>Language:</strong> <?php echo htmlspecialchars($row['language_used']); ?></span>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p>No books found.</p>";
        }
        ?>
    </div>
</main>

<script src="/QueryShelf/js/browse.js"></script>

</body>
</html>