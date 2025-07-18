<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Djati Intan Barokah</title>
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
        .add-button, .upload-button {
            margin-bottom: 20px;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-button {
            background-color: #28a745;
        }
        .add-button:hover {
            background-color: #218838;
        }
        .upload-button {
            background-color: #007bff;
        }
        .upload-button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background-color: #f4f4f4;
        }
        .btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #2563eb;
        }
        .btn-delete {
            background-color: #ef4444;
            margin-left: 5px;
        }
        .btn-delete:hover {
            background-color: #dc2626;
        }
        td.wrap-text {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
    <?php if(session()->getFlashdata('success')): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <h1>Data Transaksi</h1>

    <a href="<?= base_url('transaksi/add') ?>" class="add-button">Tambah Data Transaksi</a>
    <a href="<?= site_url('transaksi/showPreprocessing') ?>" class="upload-button">Preprocessing Data Transaksi</a>
    <a href="<?= site_url('transaksi/showUploadBulk') ?>" class="upload-button">Upload Bulk</a>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nomor Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Daftar Produk</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= esc($transaction['id']) ?></td>
                    <td><?= esc($transaction['nomor_transaksi']) ?></td>
                    <td><?= esc($transaction['sale_date']) ?></td>
                    <td class="wrap-text" title="<?= esc($transaction['products']) ?>">
                        <?= esc($transaction['products']) ?>
                    </td>
                    <td>
                        <a href="<?= site_url('/transaksi/detail/' . $transaction['id']) ?>" class="btn">Detail</a>
                        <a href="<?= site_url('/transaksi/delete/' . $transaction['id']) ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Tidak ada data.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
