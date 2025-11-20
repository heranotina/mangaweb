<?php
// admin/manga-list.php
require_once "../db.php";
require_once "check-admin.php";
require_once "../header.php";

$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$msg = '';
$error = '';

// XỬ LÝ XÓA
if ($action === 'delete' && $id > 0) {
    // ON DELETE CASCADE sẽ tự xóa chapters & pages nếu FK tạo đúng
    $stmt = $conn->prepare("DELETE FROM manga WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $msg = "Đã xóa truyện (ID: $id).";
    } else {
        $error = "Lỗi khi xóa: " . $conn->error;
    }
}

// LẤY DANH SÁCH TRUYỆN
$res = $conn->query("SELECT id, name, slug FROM manga ORDER BY id DESC");
?>

<div class="body-container">
  <div class="admin-wrapper">
    <div class="admin-header">
      <h1>Quản lý truyện</h1>
      <p class="admin-sub">Danh sách tất cả truyện trong hệ thống.</p>
    </div>

    <?php if ($msg): ?>
      <div style="color:#4ade80; margin-top:8px;"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div style="color:#ff6b6b; margin-top:8px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="admin-actions" style="margin: 12px 0;">
      <a href="manga-add.php" class="admin-btn admin-btn-secondary">➕ Thêm truyện mới</a>
    </div>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên truyện</th>
            <th>Slug</th>
            <th style="width:220px;">Chức năng</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($res && $res->num_rows > 0): ?>
          <?php while ($m = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $m['id'] ?></td>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td><?= htmlspecialchars($m['slug']) ?></td>
              <td>
                <a href="manga-edit.php?id=<?= $m['id'] ?>">✏ Sửa</a> ·
                <a href="chapter-list.php?manga_id=<?= $m['id'] ?>">📄 Chapters</a> ·
                <a href="manga-list.php?action=delete&id=<?= $m['id'] ?>"
                   onclick="return confirm('Xóa truyện này? Tất cả chapter & trang cũng sẽ bị xóa!');">
                  🗑 Xóa
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4">Chưa có truyện nào.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once "../footer.php"; ?>
