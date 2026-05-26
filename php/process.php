<?php
session_start();
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ================= FORM DATA =================
    $pub_id = $_POST['pub_id'] ?? '';

    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $series = $_POST['series'];
    $edition = $_POST['edition'];
    $pages = $_POST['pages'];
    $shelf_location = $_POST['shelf_location'];
    $language_used = $_POST['language_used'];

    // ================= AUTO VALUES =================

    // Generate publication ID if empty
    if (empty($pub_id)) {
        $pub_id = "PUB" . rand(1000, 9999);
    }

    $date_published = date('Y-m-d');
    $copyright_date = date('Y');

    // ================= GET VALID PUBLISHER ID =================
    $publisherQuery = $pdo->query("SELECT publisher_id FROM publisher LIMIT 1");
    $publisher = $publisherQuery->fetch(PDO::FETCH_ASSOC);

    $publisher_id = $publisher['publisher_id'];

    // ================= INSERT =================
    if (!isset($_POST['pub_id']) || empty($_POST['pub_id'])) {

        $sql = "INSERT INTO publication
                (
                    pub_id,
                    title,
                    subtitle,
                    series,
                    edition,
                    pages,
                    shelf_location,
                    language_used,
                    date_published,
                    copyright_date,
                    publisher_id
                )

                VALUES
                (
                    :pub_id,
                    :title,
                    :subtitle,
                    :series,
                    :edition,
                    :pages,
                    :shelf_location,
                    :language_used,
                    :date_published,
                    :copyright_date,
                    :publisher_id
                )";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':pub_id' => $pub_id,
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':series' => $series,
            ':edition' => $edition,
            ':pages' => $pages,
            ':shelf_location' => $shelf_location,
            ':language_used' => $language_used,
            ':date_published' => $date_published,
            ':copyright_date' => $copyright_date,
            ':publisher_id' => $publisher_id
        ]);
    }

    // ================= UPDATE =================
    else {

        $sql = "UPDATE publication SET
                    title = :title,
                    subtitle = :subtitle,
                    series = :series,
                    edition = :edition,
                    pages = :pages,
                    shelf_location = :shelf_location,
                    language_used = :language_used
                WHERE pub_id = :pub_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':series' => $series,
            ':edition' => $edition,
            ':pages' => $pages,
            ':shelf_location' => $shelf_location,
            ':language_used' => $language_used,
            ':pub_id' => $pub_id
        ]);
    }

    // Redirect back
    header("Location: librarian_dashboard.php");
    exit();
}
?>