<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/website/assets/css/reset.css">
    <link rel="stylesheet" href="/website/assets/css/style.css">
    <title>MANGADECH</title>
  </head>
  <body>
    <header class="header">
      <div class="container header-inner">
        <nav class="nav left">
          <ul>
            <li><a href="#!">Discord</a></li>
            <li><a href="#!">Thể loại</a></li>
            <li><a href="index.php">Home</a></li>
          </ul>
        </nav>
        <div class="logo-wrap">
          <a href="index.php">
            <img
              src="https://st.truyenqqgo.com/template/frontend/images/logo-icon.png"
              alt="Logo"
              width="40"
              height="40"
            />
          </a>
        </div>
        <div class="right">
          <ul class="nav">
    <li><a href="index.php">Home</a></li>
    <li><a href="#!">Tin mới</a></li>
    <li><a href="#!">Theo dõi</a></li>

    <?php if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <li><a href="/website/admin/index.php">Admin</a></li>
    <?php endif; ?>
</ul>


          <?php if (!empty($_SESSION['user'])): ?>
    <span style="margin-right: 10px;">
      Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>
    </span>
    <a href="/website/logout.php" class="btn btn-login">Đăng xuất</a>
<?php else: ?>
    <a href="login.php" class="btn btn-login">Đăng nhập</a>
    <a href="register.php" class="btn btn-login" style="margin-left:8px;">Đăng ký</a>
<?php endif; ?>

        </div>
      </div>
    </header>
    <div class="header-spacer"></div>
