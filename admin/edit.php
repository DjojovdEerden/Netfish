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

if (!$movie) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $year = !empty($_POST['year']) ? intval($_POST['year']) : null;
    $description = trim($_POST['description']);
    
    $cover_filename = $movie['cover_image'];
    
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['cover']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            if ($cover_filename && file_exists('../uploads/covers/' . $cover_filename)) {
                unlink('../uploads/covers/' . $cover_filename);
            }
            
            $cover_filename = uniqid() . '.' . $ext;
            $upload_dir = '../uploads/covers/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            move_uploaded_file($_FILES['cover']['tmp_name'], $upload_dir . $cover_filename);
        }
    }
    
    if (empty($title) || empty($url)) {
        $error = 'Titel en video URL zijn verplicht.';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE movie 
                SET title = :title, url = :url, year = :year, description = :description, cover_image = :cover_image 
                WHERE id = :id
            ");
            $stmt->execute([
                'title' => $title,
                'url' => $url,
                'year' => $year,
                'description' => $description,
                'cover_image' => $cover_filename,
                'id' => $movie['id']
            ]);
            
            header('Location: index.php?success=updated');
            exit();
        } catch(PDOException $e) {
            $error = 'Er is een fout opgetreden bij het bijwerken van de video.';
        }
    }
}

$pageTitle = 'Video bewerken - NETFISH';
include '../includes/header.php';
?>

<div class="form-container" style="max-width: 600px;">
    <h2>Video bewerken</h2>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Titel *</label>
            <input type="text" id="title" name="title" required 
                   value="<?php echo htmlspecialchars($movie['title']); ?>">
        </div>
        
        <div class="form-group">
            <label for="url">Video URL *</label>
            <input type="text" id="url" name="url" required 
                   value="<?php echo htmlspecialchars($movie['url']); ?>">
        </div>
        
        <div class="form-group">
            <label for="year">Jaar</label>
            <input type="number" id="year" name="year" min="1900" max="2100"
                   value="<?php echo htmlspecialchars($movie['year']); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Beschrijving</label>
            <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($movie['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="cover">Cover afbeelding</label>
            <?php if ($movie['cover_image'] && file_exists('../uploads/covers/' . $movie['cover_image'])): ?>
                <img src="../uploads/covers/<?php echo htmlspecialchars($movie['cover_image']); ?>" 
                     alt="Current cover" style="max-width: 200px; display: block; margin: 10px 0;">
            <?php endif; ?>
            <input type="file" id="cover" name="cover" accept="image/*">
        </div>
        
        <button type="submit" class="btn">Video bijwerken</button>
        <a href="index.php" class="btn btn-secondary">Annuleren</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
