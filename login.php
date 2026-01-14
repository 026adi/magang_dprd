<?php
session_start();
include 'config/koneksi.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:dashboard.php");
    exit;
}

// Logika PHP Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIMAG - DPRD Kota Yogyakarta</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --dprd-blue: #1a2a6c;   /* Warna Biru Utama */
            --btn-blue: #0d6efd;    /* Warna Tombol */
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            
            /* --- BACKGROUND SETUP --- */
            /* Logikanya: Kita taruh lapisan warna biru transparan (rgba) DI ATAS gambar */
            background: 
                /* Lapisan Biru Gelap (Opasitas 80%) */
                linear-gradient(rgba(26, 42, 108, 0.85), rgba(26, 42, 108, 0.7)),
                /* Gambar Asli */
                url('assets/img/img-dprd.jpeg');
            
            background-size: cover;     /* Gambar memenuhi layar */
            background-position: center; /* Fokus ke tengah gambar */
            background-repeat: no-repeat;
        }

        .card-login {
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 12px;
            /* Shadow lembut */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: none;
        }

        .login-title {
            font-weight: 700;
            color: var(--dprd-blue);
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            background-color: #fff;
            border-color: var(--btn-blue);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .btn-login {
            background-color: var(--btn-blue);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            color: white;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .alert-danger {
            font-size: 0.9rem;
            border-radius: 8px;
            text-align: left;
        }
        
        label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 5px;
            display: block;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="card card-login">
        <h3 class="login-title">SIMAG DPRD</h3>
        <p class="login-subtitle">Sistem Informasi Magang</p>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> 
                <div><?= $error; ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin" required>
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-login">
                Login
            </button>
        </form>
        
        <div class="mt-4 text-muted" style="font-size: 0.75rem;">
            &copy; 2026 Sekretariat DPRD Kota Yogyakarta
        </div>
    </div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>