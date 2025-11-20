<?php
require_once "../db.php";
require_once "check-admin.php";

$manga_id = $_GET["manga_id"];
$msg="";

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $num   = $_POST["chapter_number"];
    $title = $_POST["title"];

    $stmt = $conn->prepare("INSERT INTO chapters (manga_id,chapter_number,title) VALUES (?,?,?)");
    $stmt->bind_param("ids", $manga_id, $num, $title);

    if ($stmt->execute()){
        $msg="Thêm chapter thành công!";
    } else {
        $msg="Lỗi: ".$stmt->error;
    }
}
?>

<h1>Thêm chapter</h1>
<p><?= $msg ?></p>
<form method="POST">
    <label>Số chapter:</label><br>
    <input name="chapter_number" type="number" step="0.1" required><br>

    <label>Tiêu đề:</label><br>
    <input name="title"><br>

    <button>Thêm</button>
</form>
