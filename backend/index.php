<?php
require 'config.php';

$sql = "SELECT posts.id, posts.title, posts.content, categories.name AS category, posts.created_at 
        FROM posts LEFT JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Blog Posts</title>
</head>
<body>
    <h1>All Blog Posts</h1>
    <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>



    <a href="../frontend/create.html">Create New Post</a>
    <hr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div>
                <h2><?= htmlspecialchars($row['title']) ?></h2>
                <p><em>Category: <?= htmlspecialchars($row['category'] ?? 'Uncategorized') ?></em></p>
                <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                <small>Posted on: <?= $row['created_at'] ?></small>
                <hr>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</body>
</html>
