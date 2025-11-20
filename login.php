<?php
require_once 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role']
            ];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Sai tài khoản hoặc mật khẩu.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="body-container">
  <div class="auth-dialog" role="dialog" aria-modal="true" aria-labelledby="authTitle">
    <div class="auth-header">
      <h2 id="authTitle">ĐĂNG NHẬP</h2>
      <p class="auth-sub">Chào mừng bạn đến với MangaKakaka 👋</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-error" style="margin-bottom:10px;color:red;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form class="auth-form" method="post">
      <div class="auth-field">
        <label for="username">Email hoặc Username</label>
        <input type="text" id="username" name="username" required />
      </div>

      <div class="auth-field">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required />
      </div>

      <button type="submit" class="btn btn-login">Đăng nhập</button>
      <p style="margin-top: 10px; font-size: 1.4rem;">
  Chưa có tài khoản?
  <a href="register.php">Đăng ký ngay</a>
</p>

    </form>
  </div>
</div>

<?php include 'footer.php'; ?>
