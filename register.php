<?php
require_once 'db.php';
session_start();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');
    if ($username === '') {
        $errors[] = 'Vui lòng nhập username.';
    }
    if ($password === '' || $confirm === '') {
        $errors[] = 'Vui lòng nhập mật khẩu và xác nhận mật khẩu.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Mật khẩu phải từ 6 ký tự trở lên.';
    }
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->fetch_assoc()) {
            $errors[] = 'Username hoặc email đã tồn tại.';
        }
    }
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param('sss', $username, $email, $hash);
        if ($stmt->execute()) {
            $success = 'Đăng ký thành công, bạn có thể đăng nhập ngay bây giờ.';
        } else {
            $errors[] = 'Có lỗi khi đăng ký, vui lòng thử lại.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="body-container">
  <div class="auth-dialog" role="dialog" aria-modal="true" aria-labelledby="authTitle">
    <div class="auth-header">
      <h2 id="authTitle">ĐĂNG KÝ</h2>
      <p class="auth-sub">Tạo tài khoản để đọc truyện trên MangaKakaka 🥰</p>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error" style="margin-bottom:10px;color:red;">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success" style="margin-bottom:10px;color:green;">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <form class="auth-form" method="post">
      <div class="auth-field">
        <label for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
          required
        />
      </div>

      <div class="auth-field">
        <label for="email">Email (không bắt buộc)</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        />
      </div>

      <div class="auth-field">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required />
      </div>

      <div class="auth-field">
        <label for="confirm">Nhập lại mật khẩu</label>
        <input type="password" id="confirm" name="confirm" required />
      </div>

      <button type="submit" class="btn btn-login">Đăng ký</button>

      <p style="margin-top: 10px; font-size: 1.4rem;">
        Đã có tài khoản?
        <a href="login.php">Đăng nhập</a>
      </p>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
