<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission (update)
    $id = intval($_POST['id']);
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_id = intval($_POST['category'] ?? 0);

    $sql = "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $title, $content, $category_id, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else if (isset($_GET['id'])) {
    // Load post data for editing
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
</head>
<body>
    <h2>Edit Blog Post</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="5" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

        <label>Category:</label><br>
        <select name="category">
            <?php
            // Fetch categories for dropdown
            $cat_sql = "SELECT * FROM categories";
            $cat_result = $conn->query($cat_sql);
            while ($cat = $cat_result->fetch_assoc()) {
                $selected = ($cat['id'] == $post['category_id']) ? "selected" : "";
                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
