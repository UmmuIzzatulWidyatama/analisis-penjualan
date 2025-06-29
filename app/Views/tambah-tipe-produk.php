<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #333;
        }
        .navbar {
            display: flex;
            background-color: #ffffff;
            padding: 10px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            align-items: center;
            justify-content: space-between;
        }
        .navbar-menu {
            display: flex;
            gap: 15px;
        }
        .navbar-menu a {
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .navbar-menu a.active {
            background-color: #3b82f6;
            color: white;
        }
        .navbar-menu a:hover {
            background-color: #e5e7eb;
        }
        .navbar .logout {
            text-decoration: none;
            color: #374151;
            font-size: 14px;
        }
        .navbar .logout:hover {
            color: #ef4444;
        }
        .content {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            padding: 32px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        h1 {
            font-size: 20px;
            margin-bottom: 24px;
            color: #111827;
        }
        label {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
            transition: border 0.2s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-back {
            background-color: #f9fafb;
            color: #374151;
            border: 1px solid #d1d5db;
            text-decoration: none;
            transition: background 0.2s;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-back:hover {
            background-color: #f3f4f6;
        }
        .btn-submit {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #2563eb;
        }
        span.required {
            color: #ef4444;
            margin-left: 4px;
        }
    </style>
</head>
<body> 

    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>" class="active">Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>
    
    <div class="content">
            <h1>Tambah Produk</h1>

            <?php if(session()->getFlashdata('error')): ?>
                <div style="color: #ef4444; margin-bottom: 12px;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('/tipe-produk/save') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="kode_item">Kode Item <span class="required">*</span></label>
                    <input type="text" id="kode_item" name="kode_item" required>
                </div>
                <div class="form-group">
                    <label for="name">Nama Produk <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="buttons">
                    <a href="<?= site_url('/tipe-produk') ?>" class="btn-back">Kembali</a>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
    </div>

</body>
</html>
