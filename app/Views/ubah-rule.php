<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rule</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
        }
        .form-container {
            background-color: white;
            padding: 40px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 400px;
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

        .input-with-symbol {
            display: flex;
            align-items: center;
        }

        .input-with-symbol input[type="number"] {
            flex: 1;
        }

        .input-with-symbol span {
            margin-left: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: flex-start; /* Rata kanan biar rapih */
            gap: 10px; /* Jarak antar tombol */
            margin-top: 10px;
        }

        .btn-back {
            display: inline-block; /* Ini penting biar sejajar */
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
        input[readonly] {
            background-color: #f3f4f6; /* abu-abu terang */
            color: #6b7280; /* teks sedikit gelap */
            cursor: not-allowed;
        }
    </style>
</head>
<body> 

    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>" class="active">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>">Tipe Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>
    
    <div class="content">
        <div class="form-container">
            <h1>Ubah Rule</h1>
            <form action="<?= site_url('/rule/update/'.$rule['id']) ?>" method="post"> 
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="Minimum Support" readonly>
                </div>
                <div class="form-group">
                    <label for="minimum_value">Minimum Value <span class="required">*</span></label>
                    <div class="input-with-symbol">
                        <input type="number" id="minimum_value" name="minimum_value" value="<?= esc($rule['value']) ?>" min="0" max="100" step="1" required>
                        <span>%</span>
                    </div>
                </div>
                <div class="buttons">
                    <a href="<?= site_url('/rule') ?>" class="btn-back">Kembali</a>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
