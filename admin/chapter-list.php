<?php
require_once "../db.php";
require_once "check-admin.php";

$manga_id = $_GET["manga_id"];

$manga = $conn->query("SELECT name FROM manga WHERE id=$manga_id")->fetch_assoc();
$res = $conn->query("SELECT * FROM chapters WHERE manga_id=$manga_id ORDER BY chapter_number DESC");
?>
<h1>Chapters của truyện: <?= $manga["name"] ?></h1>

<a href="chapter-add.php?manga_id=<?= $manga_id ?>">+ Thêm chapter</a>

<table border="1" cellpadding="10">
    <tr>
        <th>Chapter</th>
        <th>Tiêu đề</th>
        <th>Trang</th>
    </tr>

<?php while ($c = $res->fetch_assoc()): ?>
<tr>
    <td><?= $c["chapter_number"] ?></td>
    <td><?= $c["title"] ?></td>
    <td><a href="page-add.php?chapter_id=<?= $c["id"] ?>">Thêm trang</a></td>
</tr>
<?php endwhile; ?>
</table>
