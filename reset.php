<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';
$valid = false;

if (!isset($_GET['user_id']) || !isset($_GET['hash'])) {
    $error = 'Ongeldige reset link.';
} else {
    $user_id = $_GET['user_id'];
    $hash = $_GET['hash'];
    
    $stmt = $pdo->prepare("
        SELECT * FROM passwords 
        WHERE user_id = :user_id 
        AND reset_hash = :hash 
        AND reset_expires > NOW()
    ");
    $stmt->execute([
        'user_id' => $user_id,
        'hash' => $hash
    ]);
    $reset = $stmt->fetch();
    
    if (!$reset) {
        $error = 'Deze reset link is ongeldig of verlopen.';
    } else {
        $valid = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $valid) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($password)) {
        $error = 'Vul een nieuw wachtwoord in.';
    } elseif ($password !== $confirm_password) {
        $error = 'Wachtwoorden komen niet overeen.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 karakters zijn.';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            UPDATE passwords 
            SET password_hash = :password_hash, reset_hash = NULL, reset_expires = NULL 
            WHERE user_id = :user_id
        ");
        $stmt->execute([
            'password_hash' => $password_hash,
            'user_id' => $user_id
        ]);
        
        $success = 'Wachtwoord succesvol gewijzigd! Je kunt nu inloggen.';
        $valid = false;
    }
}

$pageTitle = 'Wachtwoord resetten - NETFISH';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Nieuw wachtwoord instellen</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <div class="form-link">
            <a href="login.php">Ga naar inloggen</a>
        </div>
    <?php endif; ?>
    
    <?php if ($valid && !$success): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Nieuw wachtwoord</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Bevestig wachtwoord</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Wachtwoord wijzigen</button>
        </form>
    <?php endif; ?>
    
    <?php if (!$valid && !$success): ?>
        <div class="form-link">
            <a href="forgot-password.php">Nieuwe reset link aanvragen</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
