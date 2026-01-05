<?php
session_start();
include 'config/koneksi.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); // Menggunakan MD5 sesuai database kita

    $query  = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);
    $cek    = mysqli_num_rows($result);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['username']     = $data['username'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['status']       = "login";
        header("location:dashboard.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login SIMAG DPRD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d); height: 100vh; display: flex; align-items: center; }
        .card-login { width: 400px; margin: auto; border-radius: 15px; border: none; }
    </style>
</head>
<body>
    <div class="card card-login shadow-lg">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-dark">SIMAG DPRD JOGJA</h4>
                <p class="text-muted small">Silakan masuk untuk mengelola data</p>
            </div>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2 small"><?= $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="admin_dprd" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-2">LOG IN</button>
            </form>
        </div>
    </div>
</body>
</html>