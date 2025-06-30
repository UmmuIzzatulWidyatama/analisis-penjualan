<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
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
            align-items: flex-start;
            padding: 40px 20px;
        }
        .form-container {
            background-color: white;
            padding: 32px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 600px;
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
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin-top: 20px;
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
        .btn-delete {
            background-color: #ef4444;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f9fafb;
        }
        span.required {
            color: #ef4444;
            margin-left: 4px;
        }
        input[type="text"].form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
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
        <div class="form-container">
            <h1>Tambah Data Transaksi</h1>

            <?php if(session()->getFlashdata('error')): ?>
                <div style="color: #ef4444; margin-bottom: 12px;">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('/transaksi/save') ?>" method="post" onsubmit="return validateForm()">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="nomor_transaksi">Nomor Transaksi <span class="required">*</span></label>
                    <input type="text" class="form-control" name="nomor_transaksi" required>
                </div>
                
                <div class="form-group">
                    <label for="sale_date">Tanggal Transaksi <span class="required">*</span></label>
                    <input type="date" id="sale_date" name="sale_date" required>
                </div>

                <div class="form-group">
                    <label for="product_select">Produk <span class="required">*</span></label>
                    <div style="display: flex; gap: 10px;">
                        <select id="product_select">
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id'] ?>"><?= esc($product['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" onclick="addProduct()" class="btn-submit">Tambah</button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="product_list"></tbody>
                </table>

                <div class="buttons">
                    <a href="<?= site_url('/transaksi') ?>" class="btn-back">Kembali</a>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedProducts = [];

        function addProduct() {
            const select = document.getElementById('product_select');
            const productId = select.value;
            const productName = select.options[select.selectedIndex].text;

            if (!productId || selectedProducts.includes(productId)) return;

            selectedProducts.push(productId);

            const tbody = document.getElementById('product_list');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${selectedProducts.length}</td>
                <td>
                    ${productName}
                    <input type="hidden" name="product_type_ids[]" value="${productId}">
                </td>
                <td><button type="button" class="btn-delete" onclick="removeProduct(this, '${productId}')">Hapus</button></td>
            `;

            tbody.appendChild(row);
        }

        function removeProduct(button, id) {
            selectedProducts = selectedProducts.filter(pid => pid !== id);
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#product_list tr');
            rows.forEach((row, index) => {
                row.cells[0].innerText = index + 1;
            });
        }

        function validateForm() {
            if (selectedProducts.length === 0) {
                alert('Silakan tambahkan minimal 1 produk sebelum menyimpan transaksi.');
                return false;
            }
            return true;
        }

    </script>
</body>
</html>
