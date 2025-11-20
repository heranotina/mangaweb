<?php
// admin/manga-edit.php
require_once "../db.php";
require_once "check-admin.php";
require_once "../header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo '<div class="body-container"><div class="admin-wrapper">ID không hợp lệ.</div></div>';
    require "../footer.php";
    exit;
}

// Lấy truyện hiện tại
$stmt = $conn->prepare("SELECT * FROM manga WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$manga = $stmt->get_result()->fetch_assoc();

if (!$manga) {
    echo '<div class="body-container"><div class="admin-wrapper">Không tìm thấy truyện.</div></div>';
    require "../footer.php";
    exit;
}

$msg = '';
$error = '';

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $slug        = trim($_POST['slug'] ?? '');
    $cover_url   = trim($_POST['cover_url'] ?? '');
    $author      = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = trim($_POST['status'] ?? 'ONGOING');

    if ($name === '' || $slug === '') {
        $error = 'Vui lòng nhập đầy đủ Tên truyện và Slug.';
    } else {
        // Kiểm tra slug trùng với truyện khác
        $check = $conn->prepare("SELECT id FROM manga WHERE slug = ? AND id <> ? LIMIT 1");
        $check->bind_param("si", $slug, $id);
        $check->execute();
        $dup = $check->get_result()->fetch_assoc();

        if ($dup) {
            $error = 'Slug đã được dùng cho truyện khác, hãy chọn slug khác.';
        } else {
            $stmtUp = $conn->prepare("
                UPDATE manga
                SET name = ?, slug = ?, cover_url = ?, author = ?, description = ?, status = ?
                WHERE id = ?
            ");
            $stmtUp->bind_param(
                "ssssssi",
                $name, $slug, $cover_url, $author, $description, $status, $id
            );

            if ($stmtUp->execute()) {
                $msg = 'Cập nhật truyện thành công.';
                // cập nhật lại biến hiển thị
                $manga['name']        = $name;
                $manga['slug']        = $slug;
                $manga['cover_url']   = $cover_url;
                $manga['author']      = $author;
                $manga['description'] = $description;
                $manga['status']      = $status;
            } else {
                $error = 'Lỗi khi cập nhật: ' . $conn->error;
            }
        }
    }
}
?>

<div class="body-container">
  <div class="admin-wrapper">
    <div class="admin-header">
      <h1>Sửa truyện</h1>
      <p class="admin-sub">ID: <?= $manga['id'] ?></p>
    </div>

    <?php if ($msg): ?>
      <div style="color:#4ade80; margin:8px 0;"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div style="color:#ff6b6b; margin:8px 0;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" style="margin-top:16px;">
      <div class="auth-field">
        <label for="name">Tên truyện</label>
        <input type="text" id="name" name="name"
               value="<?= htmlspecialchars($manga['name']) ?>" required>
      </div>

      <div class="auth-field">
        <label for="slug">Slug (đường dẫn không dấu, dùng dấu -)</label>
        <input type="text" id="slug" name="slug"
               value="<?= htmlspecialchars($manga['slug']) ?>" required>
      </div>

      <div class="auth-field">
        <label for="cover_url">Ảnh bìa (URL)</label>
        <input type="text" id="cover_url" name="cover_url"
               value="<?= htmlspecialchars($manga['cover_url']) ?>">
      </div>

      <div class="auth-field">
        <label for="author">Tác giả</label>
        <input type="text" id="author" name="author"
               value="<?= htmlspecialchars($manga['author']) ?>">
      </div>

      <div class="auth-field">
        <label for="status">Trạng thái</label>
        <select id="status" name="status">
          <option value="ONGOING"   <?= $manga['status'] === 'ONGOING'   ? 'selected' : '' ?>>Đang ra</option>
          <option value="COMPLETED" <?= $manga['status'] === 'COMPLETED' ? 'selected' : '' ?>>Hoàn thành</option>
          <option value="PAUSED"    <?= $manga['status'] === 'PAUSED'    ? 'selected' : '' ?>>Tạm dừng</option>
        </select>
      </div>

      <div class="auth-field">
        <label for="description">Mô tả</label>
        <textarea id="description" name="description" rows="4"
                  style="width:100%; border-radius:10px; padding:8px;">
<?= htmlspecialchars($manga['description']) ?>
        </textarea>
      </div>

      <button type="submit" class="admin-btn admin-btn-secondary" style="margin-top:12px;">
        💾 Lưu thay đổi
      </button>

      <a href="manga-list.php" style="margin-left:10px;">← Quay lại danh sách</a>
    </form>
  </div>
</div>

<?php require_once "../footer.php"; ?>
