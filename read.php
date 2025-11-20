<?php
require_once 'db.php';
require_once 'header.php';

$chapter_id = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 0;

if ($chapter_id <= 0) {
    echo '<div class="body-container">Không tìm thấy chapter.</div>';
    require 'footer.php';
    exit;
}

$stmt = $conn->prepare("
  SELECT c.*, m.name AS manga_name, m.slug
  FROM chapters c
  JOIN manga m ON c.manga_id = m.id
  WHERE c.id = ?
  LIMIT 1
");
$stmt->bind_param('i', $chapter_id);
$stmt->execute();
$cres = $stmt->get_result();
$chapter = $cres->fetch_assoc();

if (!$chapter) {
    echo '<div class="body-container">Chapter không tồn tại.</div>';
    require 'footer.php';
    exit;
}

$stmtPages = $conn->prepare("SELECT * FROM chapter_pages WHERE chapter_id = ? ORDER BY page_number ASC");
$stmtPages->bind_param('i', $chapter_id);
$stmtPages->execute();
$pages = $stmtPages->get_result();
?>

<div class="body-container">
  <div class="reader-header">
    <a href="manga.php?slug=<?= urlencode($chapter['slug']) ?>">
      <?= htmlspecialchars($chapter['manga_name']) ?>
    </a>
    &raquo;
    Chapter <?= rtrim(rtrim($chapter['chapter_number'], '0'), '.') ?>
    <?= $chapter['title'] ? ' - ' . htmlspecialchars($chapter['title']) : '' ?>
  </div>

  <div class="reader-pages">
    <?php if ($pages && $pages->num_rows > 0): ?>
      <?php while ($p = $pages->fetch_assoc()): ?>
        <div class="reader-page">
          <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Page <?= $p['page_number'] ?>" />
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Chapter này chưa có trang nào.</p>
    <?php endif; ?>
  </div>
</div>

<?php require_once 'footer.php'; ?>
