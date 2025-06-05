<?php
require '../backend/config.php';

$categoriesResult = $conn->query("SELECT * FROM categories");
$categories = [];
if ($categoriesResult && $categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : 'NULL';

    $sql = "INSERT INTO posts (title, content, category_id) VALUES ('$title', '$content', " . ($category_id === 'NULL' ? 'NULL' : $category_id) . ")";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create New Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container my-5" style="max-width: 600px;">
        <h1 class="mb-4">Create New Post</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="title" 
                    name="title" 
                    required 
                    placeholder="Enter post title" 
                />
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea 
                    class="form-control" 
                    id="content" 
                    name="content" 
                    rows="6" 
                    required 
                    placeholder="Write your post content here..."
                ></textarea>
            </div>

            <div class="mb-4">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
            <a href="index.php" class="btn btn-secondary ms-2">Back to Posts</a>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper (optional if you need JS components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
