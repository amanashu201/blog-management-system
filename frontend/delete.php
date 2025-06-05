<?php
require '../backend/config.php';

$message = '';

// Handle deletion if POST request with id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $post_id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $message = 'Post deleted successfully.';
        } else {
            $message = 'Post not found or already deleted.';
        }
        $stmt->close();
    } else {
        $message = "Prepare failed: " . $conn->error;
    }
}

// Fetch all posts to display
$result = $conn->query("SELECT posts.id, posts.title, categories.name AS category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id ORDER BY posts.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delete Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container my-5" style="max-width: 900px;">
        <h1 class="mb-4">All Posts</h1>

        <?php if ($message): ?>
            <div class="alert <?= $message === 'Post deleted successfully.' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($post = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $post['id'] ?></td>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></td>
                                <td>
                                    <form method="POST" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this post?');" class="m-0">
                                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>

        <a href="../frontend/index.php" class="btn btn-secondary mt-3">Back to Posts</a>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
