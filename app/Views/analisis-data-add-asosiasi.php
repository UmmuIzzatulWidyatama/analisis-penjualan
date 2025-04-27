<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itemset 2</title>
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
            justify-content: space-between;
        }
        .navbar-menu {
            display: flex;
            gap: 15px;
        }
        .navbar-menu a {
            text-decoration: none;
            color: #333;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .navbar-menu a.active {
            background-color: #007bff;
            color: white;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #f0f0f0;
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
        .alert-info {
            padding: 10px;
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>">Tipe Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>" class="active">Analisis Data</a>
        </div>
    </div>
    <div class="container">
        <div class="progress-bar">
            <div class="progress-step">
                <div class="progress-circle">1</div>
                Main Info
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle ">2</div>
                Itemset 1
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle ">3</div>
                Itemset 2
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">4</div>
                Itemset 3
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle active">5</div>
                Asosiasi
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">6</div>
                Kesimpulan
            </div>
        </div>

        <h1>Asosiasi 2 Item</h1>
        <table>
            <thead>
                <tr>
                    <th>Rule</th>
                    <th>Confidence</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rules2 as $rule): ?>
                    <tr style="<?= $rule['is_below_confidence_threshold'] ? 'background-color: #f8d7da;' : '' ?>">
                        <td><?= esc($rule['from_item_name']) ?> → <?= esc($rule['to_item_name']) ?></td>
                        <td><?= esc($rule['confidence_percent']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h1>Asosiasi 3 Item</h1>
        <table>
            <thead>
                <tr>
                    <th>Rule</th>
                    <th>Confidence</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rules3 as $rule): ?>
                    <tr style="<?= $rule['is_below_confidence_threshold'] ? 'background-color: #f8d7da;' : '' ?>">
                        <td><?= esc($rule['from_item_name']) ?> & <?= esc($rule['from_item_2_name']) ?> → <?= esc($rule['to_item_name']) ?></td>
                        <td><?= esc($rule['confidence_percent']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="form-actions">
            <a href="<?= base_url('analisis-data/add') ?>" class="btn btn-secondary">Kembali</a>
            <a href="<?= base_url('analisis-data/kesimpulan') ?>" class="btn">Selanjutnya</a>
        </div>
        
    </div>
</body>
</html>
