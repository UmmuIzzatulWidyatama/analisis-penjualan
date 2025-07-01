<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Djati Intan Barokah</title>
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
            margin-bottom: 20px;
            background-color: #f9fafb;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table th, table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 600;
        }
        .btn-back {
            display: inline-block;
            background-color: #f9fafb;
            color: #374151;
            border: 1px solid #d1d5db;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-back:hover {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body> 
    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>">Produk</a>
            <a href="<?= base_url('transaksi') ?>" class="active">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>

    <div class="content">
        <h1>Detail Data Transaksi</h1>

        <label for="nomor_transaksi">Nomor Transaksi</label>
        <input type="text" id="nomor_transaksi" value="<?= esc($transaction['nomor_transaksi']) ?>" readonly>

        <label for="sale_date">Tanggal Transaksi</label>
        <input type="text" id="sale_date" value="<?= esc($transaction['sale_date']) ?>" readonly>

        <label>List Produk</label>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $i => $item): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($item['product_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="<?= site_url('/transaksi') ?>" class="btn-back">Kembali</a>
    </div>
</body>
</html>
