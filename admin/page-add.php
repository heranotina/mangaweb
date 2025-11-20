<?php
require_once "../db.php";
require_once "check-admin.php";
require_once "../header.php";

$chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;

if ($chapter_id <= 0) {
    echo '<div class="body-container"><div class="admin-wrapper"><p>chapter_id không hợp lệ.</p></div></div>';
    require "../footer.php";
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.chapter_number, c.title, m.name AS manga_name
    FROM chapters c
    JOIN manga m ON c.manga_id = m.id
    WHERE c.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $chapter_id);
$stmt->execute();
$chapter = $stmt->get_result()->fetch_assoc();

if (!$chapter) {
    echo '<div class="body-container"><div class="admin-wrapper"><p>Không tìm thấy chapter.</p></div></div>';
    require "../footer.php";
    exit;
}

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $page_number = isset($_POST["page_number"]) ? (int)$_POST["page_number"] : 0;
    $image_url   = trim($_POST["image_url"] ?? '');

    if ($page_number <= 0) {
        $error = "Số trang phải > 0.";
    } elseif ($image_url === '') {
        $error = "Vui lòng nhập URL ảnh.";
    } else {
        $stmtIns = $conn->prepare("
            INSERT INTO chapter_pages (chapter_id, page_number, image_url)
            VALUES (?, ?, ?)
        ");
        $stmtIns->bind_param("iis", $chapter_id, $page_number, $image_url);

        if ($stmtIns->execute()) {
            $msg = "Thêm trang thành công! (ID: " . $stmtIns->insert_id . ")";
        } else {
            $error = "Lỗi khi thêm trang: " . $conn->error;
        }
    }
}
?>

<div class="body-container">
  <div class="admin-wrapper">
    <div class="admin-header">
      <h1>Thêm trang cho chapter 
        <?= rtrim(rtrim($chapter['chapter_number'], '0'), '.') ?>
        <?= $chapter['title'] ? ' - ' . htmlspecialchars($chapter['title']) : '' ?>
      </h1>
      <p class="admin-sub">
        Thuộc truyện: <strong><?= htmlspecialchars($chapter['manga_name']) ?></strong>
      </p>
    </div>

    <?php if ($msg): ?>
      <div class="alert alert-success" style="margin: 8px 0; color:#4ade80;">
        <?= htmlspecialchars($msg) ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-error" style="margin: 8px 0; color:#ff6b6b;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" style="margin-top:16px;">
      <div class="auth-field">
        <label for="page_number">Số trang</label>
        <input type="number" id="page_number" name="page_number" min="1" required />
      </div>

      <div class="auth-field">
        <label for="image_url">Ảnh (URL)</label>
        <input type="text" id="image_url" name="image_url" required />
      </div>

      <button type="submit" class="admin-btn admin-btn-secondary" style="margin-top:12px;">
        ➕ Thêm trang
      </button>
    </form>

    <p style="margin-top:14px;">
      <a href="chapter-list.php?manga_id=<?= (int)$chapter_id ?>">← Quay lại danh sách chapter</a>
    </p>
  </div>
</div>

<?php require "../footer.php"; ?>
