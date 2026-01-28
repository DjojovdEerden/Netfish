<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM movie WHERE id = :id");
$stmt->execute(['id' => $_GET['id']]);
$movie = $stmt->fetch();

if ($movie) {
    if ($movie['cover_image'] && file_exists('../uploads/covers/' . $movie['cover_image'])) {
        unlink('../uploads/covers/' . $movie['cover_image']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM movie WHERE id = :id");
    $stmt->execute(['id' => $movie['id']]);
}

header('Location: index.php?success=deleted');
exit();
?>
