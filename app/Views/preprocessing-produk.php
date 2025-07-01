<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .upload-section {
            margin-bottom: 20px;
        }
        .upload-section label {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
            color: #374151;
        }
        .upload-section input[type="file"] {
            padding: 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .button-group button {
            padding: 8px 16px;
            border: none;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s, opacity 0.2s;
        }
        .btn-upload {
            background-color: #3b82f6;
        }
        .btn-upload:hover {
            background-color: #2563eb;
        }
        .btn-upload:disabled {
            background-color: #93c5fd;
            cursor: not-allowed;
            opacity: 0.7;
        }
        .btn-download {
            background-color: #10b981;
        }
        .btn-download:hover {
            background-color: #059669;
        }
        .notice {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f9fafb;
            color: #111827;
        }
        .invalid-row {
            background-color: #ffe5e5;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .action-buttons button {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background 0.2s, border 0.2s;
        }
        .action-buttons .back {
            background-color: #fff;
            color: #111827;
            border: 1px solid #d1d5db;
        }
        .action-buttons .back:hover {
            background-color: #f9fafb;
        }
        .action-buttons .save {
            background-color: #3b82f6;
            color: #fff;
            border: 1px solid #3b82f6;
        }
        .action-buttons .save:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .action-buttons .save:disabled {
            background-color: #93c5fd;
            border-color: #93c5fd;
            cursor: not-allowed;
        }
        .button-group .back {
            background-color: #f9fafb;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .button-group .back:hover {
            background-color: #f9fafb;
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
    <h1>Preprocessing Produk</h1>

    <form action="<?= base_url('/tipe-produk/uploadPreprocessing') ?>" method="post" enctype="multipart/form-data">
        <div class="upload-section">
            <label>Upload File</label>
            <input type="file" id="fileInput" name="file" accept=".xlsx" onchange="checkFileSelected()">
        </div>
        <div class="button-group">
            <button type="submit" id="uploadBtn" class="btn-upload" disabled>Upload</button>
        </div>
    </form>

    <script>
    function checkFileSelected() {
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('uploadBtn');
        uploadBtn.disabled = fileInput.files.length === 0;
    }
    </script>


</body>
</html>
