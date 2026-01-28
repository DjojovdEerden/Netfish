<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Vul alle velden in.';
    } else {
        $stmt = $pdo->prepare("
            SELECT u.*, p.password_hash
            FROM user u
            LEFT JOIN passwords p ON u.id = p.user_id
            WHERE u.username = :username
        ");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            if ($user['is_admin']) {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = 'Ongeldige gebruikersnaam of wachtwoord.';
        }
    }
}

$pageTitle = 'Inloggen - NETFISH';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Inloggen</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" required 
                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Inloggen</button>
    </form>
    
    <div class="form-link">
        <a href="forgot-password.php">Wachtwoord vergeten?</a>
    </div>
    
    <div class="form-link">
        Nog geen account? <a href="register.php">Registreren</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
