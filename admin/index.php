<?php
require_once "../db.php";
require_once "../header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<div class="body-container">
  <div class="admin-wrapper">
    <div class="admin-header">
      <h1>Trang quản trị</h1>
      <p>Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong> 👋</p>
      <p class="admin-sub">
        Ở đây Onii-chan có thể quản lý truyện, chapter và các trang truyện.
      </p>
    </div>

    <div class="admin-actions">
      <a href="manga-list.php" class="admin-btn">📚 Quản lý truyện</a>
      <a href="manga-add.php" class="admin-btn admin-btn-secondary">➕ Thêm truyện mới</a>
      <!-- sau này thêm tiếp các mục khác -->
      <!-- <a href="users.php" class="admin-btn admin-btn-ghost">👤 Quản lý user</a> -->
    </div>
  </div>
</div>

<?php require_once "../footer.php"; ?>
