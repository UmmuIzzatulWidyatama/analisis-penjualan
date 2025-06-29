<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Analisis Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .navbar {
            display: flex;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            align-items: center;
            justify-content: space-between;
        }
        .navbar-menu {
            display: flex;
            gap: 15px;
        }
        .navbar-menu a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .navbar-menu a.active {
            background-color: #007bff;
            color: white;
        }
        .navbar-menu a:hover {
            background-color: #e0e0e0;
        }
        .navbar .logout {
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        .navbar .logout:hover { 
            color: #d9534f;
        }
        .content {
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .progress-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 14px;
        }
        .progress-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .progress-circle.active {
            background-color: black;
        }
        .progress-line {
            flex-grow: 1;
            height: 2px;
            background-color: #007bff;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .form-group label {
            width: 200px;
            margin-right: 10px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: none;
            height: 100px;
        }
        .form-actions {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
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
            <a href="<?= base_url('tipe-produk') ?>">Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>" class="active">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>
    <div class="container">
    <div class="progress-bar">
            <div class="progress-step">
                <div class="progress-circle active">1</div>
                Main Info
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">2</div>
                Itemset 1
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">3</div>
                Itemset 2
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">4</div>
                Itemset 3
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">5</div>
                Asosiasi
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">6</div>
                Kesimpulan
            </div>
        </div>
        <br>
        <h1>Main Info</h1>
        <form action="<?= base_url('analisis-data/save'); ?>" method="post">
            <div class="form-group">
                <label for="start_date">Tanggal Awal <span class="required">*</span></label>
                <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">Tanggal Akhir <span class="required">*</span></label>
                <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi <span class="required">*</span></label>
                <textarea name="description" required><?= esc($description ?? '') ?></textarea>
            </div>
            <div class="form-actions">
                <a href="<?= base_url('analisis-data') ?>" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn">Selanjutnya</button>
            </div>
        </form>
    </div>
</body>
</html>
