<?php
require '../backend/config.php';

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, categories.name AS category_name 
    FROM posts LEFT JOIN categories ON posts.category_id = categories.id
    ORDER BY posts.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Management System</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
    <h1 class="mb-4 text-center">All Blog Posts</h1>

    <!-- Navigation for Create Post -->
    <div class="mb-4 d-flex justify-content-center gap-2">
        <a href="/create.php" class="btn btn-primary">Create Blog</a>
        <a href="/edit.php" class="btn btn-warning">Edit</a>
        <a href="/delete.php" class="btn btn-danger">Delete</a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="row row-cols-1 g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col">
            <article class="card shadow-sm">
                <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($row['title']) ?></h2>
                <h6 class="card-subtitle mb-2 text-muted">
                    <?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?>
                </h6>
                <p class="card-text"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                <small class="text-secondary">Posted on <?= $row['created_at'] ?></small>
                </div>
            </article>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No blog posts found.</div>
    <?php endif; ?>
    </div>
    <!-- Bootstrap JS (optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
