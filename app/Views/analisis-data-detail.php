<!-- Pastikan ini berada di views/analisis-data-detail.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Analisis Data</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; background: #f3f4f6; color: #111827; }
        .navbar { display: flex; justify-content: space-between; background: white; padding: 12px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .navbar-menu { display: flex; gap: 16px; }
        .navbar-menu a { text-decoration: none; color: #374151; font-size: 14px; padding: 8px 12px; border-radius: 6px; }
        .navbar-menu a.active { background: #3b82f6; color: white; }
        .logout { text-decoration: none; color: #374151; }
        .logout:hover { color: #ef4444; }

        .container { max-width: 900px; margin: 40px auto; background: white; padding: 32px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }

        h1 { font-size: 22px; margin-bottom: 24px; }
        h2 { font-size: 18px; margin-top: 32px; margin-bottom: 12px; }
        .highlight-box { background: #ecfdf5; padding: 20px; border-left: 4px solid #10b981; margin-bottom: 20px; }
        .highlight-box span { display: block; margin: 4px 0; font-size: 14px; }
        .recommend-box { background: #fefce8; border-left: 4px solid #facc15; padding: 16px; font-size: 14px; margin-bottom: 24px; }
        .recommend-box strong { color: #b45309; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 20px; font-size: 14px; }
        table, th, td { border: 1px solid #e5e7eb; }
        th { background: #f9fafb; padding: 10px; text-align: left; font-weight: 600; }
        td { padding: 10px; }
        tr.low-support { background-color: #fde2e2; }
        .btn-back { background: #f9fafb; border: 1px solid #d1d5db; padding: 10px 16px; border-radius: 6px; font-weight: 600; color: #374151; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn-back:hover { background: #f3f4f6; }
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
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
    </div>

    <div class="container">
        <h1>Detail Analisis Data</h1>

        <p><strong>Deskripsi Analisis:</strong> <?= esc($analisis['description']) ?></p>

        <div class="highlight-box">
            <span><strong>Periode:</strong> <?= esc($analisis['start_date']) ?> - <?= esc($analisis['end_date']) ?></span>
            <span><strong>Total Transaksi:</strong> <?= esc($analisis['transaction_count']) ?></span>
            <span><strong>Minimum Support:</strong> <?= esc($analisis['minimum_support']) ?>%</span>
            <span><strong>Minimum Confidence:</strong> <?= esc($analisis['minimum_confidence']) ?>%</span>
        </div>

        <?php if (!empty($recommendation)): ?>
        <div class="recommend-box">
            Berdasarkan hasil analisis, kombinasi terbaik adalah <strong><?= esc($recommendation['from']) ?> → <?= esc($recommendation['to']) ?></strong>
            dengan confidence <strong><?= esc($recommendation['confidence']) ?>%</strong>.
            Disarankan untuk fokus pada promosi atau strategi kombinasi produk ini.
        </div>
        <?php endif; ?>

        <!-- Itemset 1 -->
        <h2>Itemset 1</h2>
        <p>Minimum Support: <?= esc($analisis['minimum_support']) ?>%</p>
        <table>
            <thead>
                <tr><th>Itemset</th><th>Frekuensi</th><th>Support</th></tr>
            </thead>
            <tbody>
                <?php foreach ($itemset1 as $row): ?>
                <tr class="<?= $row['is_below_threshold'] ? 'low-support' : '' ?>">
                    <td><?= esc($row['product_name']) ?></td>
                    <td><?= esc($row['support_count']) ?></td>
                    <td><?= esc($row['support_percent']) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Itemset 2 -->
        <h2>Itemset 2</h2>
        <p>Minimum Support: <?= esc($analisis['minimum_support']) ?>%</p>
        <table>
            <thead>
                <tr><th>Itemset</th><th>Frekuensi</th><th>Support</th></tr>
            </thead>
            <tbody>
                <?php foreach ($itemset2 as $row): ?>
                <tr class="<?= $row['is_below_threshold'] ? 'low-support' : '' ?>">
                    <td><?= esc($row['product_names']) ?></td>
                    <td><?= esc($row['support_count']) ?></td>
                    <td><?= esc($row['support_percent']) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Itemset 3 -->
        <h2>Itemset 3</h2>
        <p>Minimum Support: <?= esc($analisis['minimum_support']) ?>%</p>
        <table>
            <thead>
                <tr><th>Itemset</th><th>Frekuensi</th><th>Support</th></tr>
            </thead>
            <tbody>
                <?php foreach ($itemset3 as $row): ?>
                <tr class="<?= $row['is_below_threshold'] ? 'low-support' : '' ?>">
                    <td><?= esc($row['product_names']) ?></td>
                    <td><?= esc($row['support_count']) ?></td>
                    <td><?= esc($row['support_percent']) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Asosiasi 2 Item -->
        <h2>Asosiasi 2 Item</h2>
        <table>
            <thead>
                <tr><th>Rule</th><th>Confidence</th></tr>
            </thead>
            <tbody>
                <?php foreach ($association2  as $row): ?>
                <tr>
                    <td><?= esc($row['rule']) ?></td>
                    <td><?= esc($row['confidence']) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Asosiasi 3 Item -->
        <h2>Asosiasi 3 Item</h2>
        <table>
            <thead>
                <tr><th>Rule</th><th>Confidence</th></tr>
            </thead>
            <tbody>
                <?php foreach ($association3 as $row): ?>
                <tr>
                    <td><?= esc($row['rule']) ?></td>
                    <td><?= esc($row['confidence']) ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="<?= base_url('/analisis-data') ?>" class="btn-back">Kembali</a>
    </div>
</body>
</html>
