<?php
require_once 'db.php';
require_once 'header.php';
$sql = "SELECT id, name, slug, cover_url, status
        FROM manga
        ORDER BY updated_at DESC
        LIMIT 12";
$mangas = $conn->query($sql);
$sqlLatest = "SELECT * FROM manga ORDER BY updated_at DESC LIMIT 4";
$latest = $conn->query($sqlLatest);


?>


<div class="body-container">
    <div class="home-section">
        <h2 class="section-title">Truyện mới cập nhật</h2>

        <div class="manga-grid">
            <?php if ($latest && $latest->num_rows > 0): ?>
                <?php while ($m = $latest->fetch_assoc()): ?>
                <a class="manga-card" href="manga.php?slug=<?= urlencode($m['slug']) ?>">
                    <div class="manga-card-cover">
                        <img src="<?= htmlspecialchars($m['cover_url']) ?>" />
                        <span class="badge-status"><?= htmlspecialchars($m['status']) ?></span>
                    </div>
                    <div class="manga-card-body">
                        <h3><?= htmlspecialchars($m['name']) ?></h3>
                        <span class="manga-card-meta">Chapter mới nhất</span>
                    </div>
                </a>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="home-section" style="margin-top:32px;">
        <h2 class="section-title">Tất cả truyện</h2>

        <div class="manga-grid">
            <?php while ($m = $mangas->fetch_assoc()): ?>
            <a class="manga-card" href="manga.php?slug=<?= urlencode($m['slug']) ?>">
                <div class="manga-card-cover">
                    <img src="<?= htmlspecialchars($m['cover_url']) ?>" />
                    <span class="badge-status"><?= htmlspecialchars($m['status']) ?></span>
                </div>
                <div class="manga-card-body">
                    <h3><?= htmlspecialchars($m['name']) ?></h3>
                    <span class="manga-card-meta">» Đọc từ đầu</span>
                </div>
            </a>
            <?php endwhile; ?>
        </div>
    </div>

</div>



<?php require_once 'footer.php'; ?>
