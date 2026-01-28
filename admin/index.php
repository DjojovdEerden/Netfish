<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

// Haal gebruikers statistieken op
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM user");
$total_users = $stmt->fetch()['total_users'];

$stmt = $pdo->query("SELECT COUNT(*) as total_admins FROM user WHERE is_admin = 1");
$total_admins = $stmt->fetch()['total_admins'];

$stmt = $pdo->query("SELECT * FROM user ORDER BY created_at DESC LIMIT 10");
$recent_users = $stmt->fetchAll();

$pageTitle = 'Admin Panel - NETFISH';
include '../includes/header.php';
?>

<div class="container">
    <div class="admin-header">
        <h2>Admin Dashboard</h2>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success">
            <?php 
            if ($_GET['success'] == 'user_deleted') echo 'Gebruiker succesvol verwijderd!';
            ?>
        </div>
    <?php endif; ?>
    
    <!-- Statistieken -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: #1a1a1a; padding: 30px; border-radius: 8px; border: 2px solid #f41313;">
            <h3 style="color: #f41313; margin-bottom: 10px;">Totaal Gebruikers</h3>
            <p style="font-size: 36px; font-weight: bold;"><?php echo $total_users; ?></p>
        </div>
        
        <div style="background: #1a1a1a; padding: 30px; border-radius: 8px; border: 2px solid #f41313;">
            <h3 style="color: #f41313; margin-bottom: 10px;">Beheerders</h3>
            <p style="font-size: 36px; font-weight: bold;"><?php echo $total_admins; ?></p>
        </div>
        
        <div style="background: #1a1a1a; padding: 30px; border-radius: 8px; border: 2px solid #f41313;">
            <h3 style="color: #f41313; margin-bottom: 10px;">Reguliere Gebruikers</h3>
            <p style="font-size: 36px; font-weight: bold;"><?php echo ($total_users - $total_admins); ?></p>
        </div>
    </div>
    
    <!-- Recente gebruikers -->
    <h3 style="color: #f41313; margin-bottom: 20px;">Recente Gebruikers</h3>
    
    <?php if (count($recent_users) > 0): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gebruikersnaam</th>
                    <th>E-mail</th>
                    <th>Type</th>
                    <th>Aangemaakt</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php if ($user['is_admin']): ?>
                                <span style="background: #f41313; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                    ADMIN
                                </span>
                            <?php else: ?>
                                <span style="background: #333; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                    USER
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d-m-Y H:i', strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                                   class="btn btn-small btn-delete" 
                                   onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                                    Verwijderen
                                </a>
                            <?php else: ?>
                                <span style="color: #999; font-size: 12px;">Eigen account</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-videos">
            <h3>Geen gebruikers</h3>
            <p>Er zijn geen gebruikers geregistreerd.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
