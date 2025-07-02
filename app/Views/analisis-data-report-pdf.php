<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            margin-top: 0;
        }
        h2 {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .empty-message {
            font-style: italic;
            color: #555;
        }
        .highlight-green {
            background-color: #e0f7fa;
            padding: 10px;
            border-left: 5px solid #00acc1;
        }
        .highlight-yellow {
            background-color: #fff8e1;
            padding: 10px;
            border-left: 5px solid #fbc02d;
            margin-top: 10px;
        }
        .highlight-orange {
            background-color: #fbe9e7;
            padding: 10px;
            border-left: 5px solid #e64a19;
            margin-top: 10px;
        }
        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Laporan Analisis Data</h1>

    <div style="background-color:#e0f2f1; padding:12px; margin-bottom:20px; border-left:5px solid #26a69a;">
        <p><strong>Deskripsi Analisis:</strong> <?= esc($analisisData['description']) ?></p>
        <p><strong>Periode:</strong> <?= esc($analisisData['start_date']) ?> - <?= esc($analisisData['end_date']) ?></p>
        <p><strong>Total Transaksi:</strong> <?= esc($analisisData['transaction_count']) ?></p>
        <p><strong>Minimum Support:</strong> <?= esc($analisisData['minimum_support']) ?>%</p>
        <p><strong>Minimum Confidence:</strong> <?= esc($analisisData['minimum_confidence']) ?>%</p>
    </div>

    <div style="background-color:#fff3e0; padding:12px; border-left:5px solid #ffb300;">
        <p><strong>Itemset 1:</strong> Produk-produk seperti <em><?= $topItemset1[0]['item_name'] ?? '-' ?></em>, <em><?= $topItemset1[1]['item_name'] ?? '-' ?></em>, dan <em><?= $topItemset1[2]['item_name'] ?? '-' ?></em> merupakan item individual yang paling sering muncul dalam transaksi.</p>
        <p><strong>Itemset 2:</strong> Kombinasi <em><?= $topItemset2[0]['produk_1'] ?? '-' ?></em> dan <em><?= $topItemset2[0]['produk_2'] ?? '-' ?></em> memiliki support tertinggi (<?= $topItemset2[0]['support_percent'] ?? '-' ?>%).</p>
        <p><strong>Itemset 3:</strong> Kombinasi <em><?= $topItemset3[0]['produk_1'] ?? '-' ?></em>, <em><?= $topItemset3[0]['produk_2'] ?? '-' ?></em>, dan <em><?= $topItemset3[0]['produk_3'] ?? '-' ?></em> paling sering muncul (<?= $topItemset3[0]['support_percent'] ?? '-' ?>%).</p>
        <p><strong>Association Rule:</strong> Aturan <?= $bestRule['from_item_name'] ?? '-' ?> -> <?= $bestRule['to_item_name'] ?? '-' ?> memiliki confidence tertinggi sebesar <?= $bestRule['confidence_percent'] ?? '-' ?>%.</p>
        <p><strong>Lift Ratio:</strong> Aturan <?= $liftResults[0]['rule'] ?? '-' ?> memiliki nilai lift tertinggi sebesar <strong><?= $liftResults[0]['lift'] ?? '-' ?></strong>.</p>
    </div>

    <div style="background-color:#f1f8e9; padding:12px; margin-top:10px; border-left:5px solid #8bc34a;">
        <strong>Insight Strategis:</strong> Hasil analisis menunjukkan bahwa produk dengan frekuensi tinggi dan asosiasi kuat layak dijadikan target promosi atau penempatan bersama untuk meningkatkan penjualan silang.
    </div>

    <?php function renderTable($title, $data, $columns, $emptyMsg) { ?>
    <div class="section">
        <h2><?= $title ?></h2>
        <?php if (!empty($data)): ?>
            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <?php foreach ($columns as $col): ?><th><?= $col ?></th><?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <?php foreach ($row as $v): ?><td><?= esc($v) ?></td><?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-message"><?= $emptyMsg ?></p>
        <?php endif; ?>
    </div>
    <?php } ?>

    <?php
    renderTable("Top 5 Frequent Itemset 1", $topItemset1, ["Nama Produk", "Support (%)"], "Tidak ada data itemset 1.");
    renderTable("Top 5 Frequent Itemset 2", array_map(fn($row) => ["{" . $row['produk_1'] . ", " . $row['produk_2'] . "}", $row['support_percent']], $topItemset2), ["Kombinasi Produk", "Support (%)"], "Tidak ada data itemset 2.");
    renderTable("Top 5 Frequent Itemset 3", array_map(fn($row) => ["{" . $row['produk_1'] . ", " . $row['produk_2'] . ", " . $row['produk_3'] . "}", $row['support_percent']], $topItemset3), ["Kombinasi Produk", "Support (%)"], "Tidak ada data itemset 3.");
    renderTable("Top 5 Asosiasi 2-Itemset", array_map(fn($row) => ["{$row['from_item_name']} -> {$row['to_item_name']}", $row['confidence_percent'] . '%'], $topRules2), ["Rule", "Confidence"], "Tidak ada data asosiasi 2-item.");
    renderTable("Top 5 Asosiasi 3-Itemset", array_map(fn($row) => ["{$row['from_item_name']} -> {$row['to_item_name']}", $row['confidence_percent'] . '%'], $topRules3), ["Rule", "Confidence"], "Tidak ada data asosiasi 3-item.");
    renderTable("Lift Ratio", array_map(fn($row) => [$row['rule'], $row['lift']], $liftResults), ["Rule", "Lift"], "Tidak ada data lift ratio.");
    ?>
</body>
</html>