<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = 'Vul je e-mailadres in.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $reset_hash = bin2hex(random_bytes(32));
            $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("
                UPDATE passwords 
                SET reset_hash = :reset_hash, reset_expires = :reset_expires 
                WHERE user_id = :user_id
            ");
            $stmt->execute([
                'reset_hash' => $reset_hash,
                'reset_expires' => $reset_expires,
                'user_id' => $user['id']
            ]);
            
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/Netfish/reset.php?user_id=" . $user['id'] . "&hash=" . $reset_hash;
            
            $success = "Reset link: <a href='$reset_link' style='color:#f41313;'>$reset_link</a><br><br>Deze link is 1 uur geldig.";
        } else {
            $error = 'Geen account gevonden met dit e-mailadres.';
        }
    }
}

$pageTitle = 'Wachtwoord vergeten - NETFISH';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Wachtwoord vergeten</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">E-mailadres</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <button type="submit" class="btn">Reset link aanvragen</button>
    </form>
    
    <div class="form-link">
        <a href="login.php">Terug naar inloggen</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
