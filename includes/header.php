<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'NETFISH'; ?></title>
    <link rel="stylesheet" href="/Netfish/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo">NETFISH</h1>
            <nav>
                <?php if (isLoggedIn()): ?>
                    <a href="/Netfish/index.php">Home</a>
                    <?php if (isAdmin()): ?>
                        <a href="/Netfish/admin/index.php">Beheer</a>
                    <?php endif; ?>
                    <a href="/Netfish/logout.php">Uitloggen</a>
                    <span class="welcome">Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <?php else: ?>
                    <a href="/Netfish/login.php">Inloggen</a>
                    <a href="/Netfish/register.php">Registreren</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
