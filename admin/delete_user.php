<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$user_id = intval($_GET['id']);

// Voorkom dat admin zichzelf verwijdert
if ($user_id == $_SESSION['user_id']) {
    header('Location: index.php?error=cannot_delete_self');
    exit();
}

try {
    // Verwijder gebruiker (passwords worden automatisch verwijderd via CASCADE)
    $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    
    header('Location: index.php?success=user_deleted');
} catch(PDOException $e) {
    header('Location: index.php?error=delete_failed');
}
exit();
?>
