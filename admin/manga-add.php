<?php
require_once "../db.php";
require_once "check-admin.php";
require_once "../header.php";

$msg = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $slug  = trim($_POST['slug']);
    $cover = trim($_POST['cover_url']);
    $author = trim($_POST['author']);
    $desc = trim($_POST['description']);

    if ($name === '' || $slug === '') {
        $error = "Tên truyện và slug không được để trống.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO manga (name, slug, cover_url, author, description)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $name, $slug, $cover, $author, $desc);

        if ($stmt->execute()) {
            $msg = "Thêm truyện thành công!";
        } else {
            $error = "Lỗi khi thêm truyện: " . $conn->error;
        }
    }
}
?>

<div class="body-container">
    <div class="admin-form-card">
        <h1>➕ Thêm truyện mới</h1>

        <?php if ($msg): ?>
            <div class="alert-success"><?= $msg ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="admin-form">

            <label>Tên truyện:</label>
            <input type="text" name="name" required>

            <label>Slug:</label>
            <input type="text" name="slug" placeholder="ten-truyen-khong-dau" required>

            <label>Ảnh bìa (URL):</label>
            <input type="text" name="cover_url">

            <label>Tác giả:</label>
            <input type="text" name="author">

            <label>Mô tả:</label>
            <textarea name="description" rows="4"></textarea>

            <button type="submit" class="admin-btn admin-btn-secondary">Thêm</button>
        </form>
    </div>
</div>

<?php require_once "../footer.php"; ?>
