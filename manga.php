<?php
require_once 'db.php';
require_once 'header.php';

$slug = $_GET['slug'] ?? '';
$slug = trim($slug);

if ($slug === '') {
    echo '<div class="body-container">Không tìm thấy truyện.</div>';
    require 'footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM manga WHERE slug = ? LIMIT 1");
$stmt->bind_param('s', $slug);
$stmt->execute();
$mres = $stmt->get_result();
$manga = $mres->fetch_assoc();

if (!$manga) {
    echo '<div class="body-container">Truyện không tồn tại.</div>';
    require 'footer.php';
    exit;
}

$stmtChap = $conn->prepare("SELECT * FROM chapters WHERE manga_id = ? ORDER BY chapter_number DESC");
$stmtChap->bind_param('i', $manga['id']);
$stmtChap->execute();
$chapters = $stmtChap->get_result();
?>

<div class="body-container">
  <div class="manga-container">
    <div class="manga-info">
      <img
        src="<?= htmlspecialchars($manga['cover_url'] ?: 'https://via.placeholder.com/200x280?text=Cover') ?>"
        alt="<?= htmlspecialchars($manga['name']) ?>"
        class="manga-cover"
      />

      <div class="manga-meta">
        <h1 class="manga-title"><?= htmlspecialchars($manga['name']) ?></h1>

        <p><strong>Tác giả:</strong> <?= htmlspecialchars($manga['author'] ?: 'Đang cập nhật') ?></p>
        <p><strong>Trạng thái:</strong> <?= htmlspecialchars($manga['status']) ?></p>

        <h3>Mô tả</h3>
        <p class="manga-desc"><?= nl2br(htmlspecialchars($manga['description'])) ?></p>
      </div>
    </div>

    <div class="chapter-block">
      <h3>DANH SÁCH CHAPTER</h3>

      <ul class="chapter-list">
        <?php if ($chapters && $chapters->num_rows > 0): ?>
          <?php while ($c = $chapters->fetch_assoc()): ?>
            <li>
              <a class="chapter-item" href="read.php?chapter=<?= $c['id'] ?>">
                Chapter <?= rtrim(rtrim($c['chapter_number'], '0'), '.') ?>
                <?= $c['title'] ? ' - ' . htmlspecialchars($c['title']) : '' ?>
              </a>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>Chưa có chapter nào.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

