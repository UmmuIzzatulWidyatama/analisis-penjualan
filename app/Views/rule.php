<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Rule</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body> 
    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>" class="active">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>">Tipe Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <!-- <a href="<?= base_url('itemset') ?>">Itemset</a> -->
            <!-- <a href="<?= base_url('asosiasi') ?>">Asosiasi</a> -->
            <a href="<?= base_url('analisis-data') ?>">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>
    <div class="content">
        <!-- Konten untuk halaman Rule -->
        <h1>Rule</h1>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Minimum Value</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rules)): ?>
                    <?php foreach ($rules as $rule): ?>
                        <tr>
                            <td><?= esc($rule['name']) ?></td>
                            <td><?= esc($rule['value']) ?>%</td>
                            <td>
                                <button class="btn">Ubah</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 