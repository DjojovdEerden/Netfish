<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

$pageTitle = 'Home - NETFISH';
include 'includes/header.php';
?>

<div class="container">
    <h2 class="page-title">Welkom bij NETFISH</h2>
    
    <div class="no-videos">
        <h3>Platform Dashboard</h3>
        <p>Je bent succesvol ingelogd als <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
        
        <?php if (isAdmin()): ?>
            <p style="margin-top: 20px;">
                <a href="admin/index.php" class="btn" style="display: inline-block; width: auto; padding: 12px 24px;">
                    Ga naar Admin Panel
                </a>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
