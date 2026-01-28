<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Alle velden zijn verplicht.';
    } elseif ($password !== $confirm_password) {
        $error = 'Wachtwoorden komen niet overeen.';
    } elseif (strlen($password) < 6) {
        $error = 'Wachtwoord moet minimaal 6 karakters zijn.';
    } else {
        try {
            $pdo->beginTransaction();
            
            $stmt = $pdo->prepare("INSERT INTO user (username, email, is_admin) VALUES (:username, :email, 0)");
            $stmt->execute([
                'username' => $username,
                'email' => $email
            ]);
            
            $user_id = $pdo->lastInsertId();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO passwords (user_id, password_hash) VALUES (:user_id, :password_hash)");
            $stmt->execute([
                'user_id' => $user_id,
                'password_hash' => $password_hash
            ]);
            
            $pdo->commit();
            $success = 'Account succesvol aangemaakt! Je kunt nu inloggen.';
            
        } catch(PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) {
                $error = 'Gebruikersnaam of e-mail bestaat al.';
            } else {
                $error = 'Er is een fout opgetreden. Probeer het opnieuw.';
            }
        }
    }
}

$pageTitle = 'Registreren - NETFISH';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Registreren</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Bevestig wachtwoord</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn">Registreren</button>
    </form>
    
    <div class="form-link">
        Heb je al een account? <a href="login.php">Inloggen</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
