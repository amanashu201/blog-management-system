<?php
require '../backend/config.php';

// Fetch all posts to choose from
$postsResult = $conn->query("SELECT id, title FROM posts");
$posts = [];
if ($postsResult && $postsResult->num_rows > 0) {
    while ($row = $postsResult->fetch_assoc()) {
        $posts[] = $row;
    }
}

// Determine selected post ID
if (isset($_POST['selected_post_id'])) {
    $post_id = intval($_POST['selected_post_id']);
} elseif (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
} else {
    $post_id = null;
}

$post = null;
if ($post_id) {
    $postResult = $conn->query("SELECT * FROM posts WHERE id = $post_id");
    if ($postResult && $postResult->num_rows > 0) {
        $post = $postResult->fetch_assoc();
    }
}

// Fetch categories
$categoriesResult = $conn->query("SELECT * FROM categories");
$categories = [];
if ($categoriesResult && $categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$error = '';

// Process form submission for updating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category_id = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? intval($_POST['category_id']) : 'NULL';

    $sql = "UPDATE posts SET title='$title', content='$content', category_id=" . ($category_id === 'NULL' ? 'NULL' : $category_id) . " WHERE id = $post_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error updating post: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container my-5" style="max-width: 700px;">
        <h1 class="mb-4">Edit Post</h1>

        <form method="POST" action="" class="mb-4">
            <div class="mb-3">
                <label for="selected_post_id" class="form-label">Select Post to Edit</label>
                <select name="selected_post_id" id="selected_post_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Post --</option>
                    <?php foreach ($posts as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $post_id == $p['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <noscript><button type="submit" class="btn btn-primary">Load Post</button></noscript>
        </form>

        <?php if ($post): ?>
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="selected_post_id" value="<?= $post_id ?>">
                <input type="hidden" name="update_post" value="1">

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="title" 
                        name="title" 
                        value="<?= htmlspecialchars($post['title']) ?>" 
                        required 
                    >
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea 
                        class="form-control" 
                        id="content" 
                        name="content" 
                        rows="6" 
                        required
                    ><?= htmlspecialchars($post['content']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="index.php" class="btn btn-secondary ms-2">Back to Posts</a>
            </form>
        <?php elseif ($post_id !== null): ?>
            <div class="alert alert-warning" role="alert">
                Post not found.
            </div>
            <a href="index.php" class="btn btn-secondary">Back to Posts</a>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS Bundle with Popper (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
