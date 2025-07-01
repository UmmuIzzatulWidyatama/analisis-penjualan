<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Djati Intan Barokah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }
        h1, h2 {
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .summary, .insight {
            margin-top: 15px;
            padding: 10px;
            border-left: 5px solid #007bff;
            background-color: #f0f8ff;
        }
        .insight {
            border-color: #ffc107;
            background-color: #fff8dc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        table th {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

    <h1>Laporan Analisis Data</h1>

    <p><strong>Deskripsi:</strong> <?= esc($analisis['description']) ?></p>

    <div class="summary">
        <p><strong>Periode:</strong> <?= esc($analisis['start_date']) ?> - <?= esc($analisis['end_date']) ?></p>
        <p><strong>Total Transaksi:</strong> <?= esc($analisis['transaction_count']) ?></p>
        <p><strong>Minimum Support:</strong> <?= esc($analisis['minimum_support']) ?>%</p>
        <p><strong>Minimum Confidence:</strong> <?= esc($analisis['minimum_confidence']) ?>%</p>
    </div>

    <div class="insight">
        <p>Kombinasi terbaik: <strong><?= esc($recommendation['from_item_name']) ?> -> <?= esc($recommendation['to_item_name']) ?></strong> dengan confidence <strong><?= esc($recommendation['confidence_percent']) ?>%</strong>.</p>
    </div>

    <h2>Lift Ratio</h2>
    <table>
        <thead>
            <tr>
                <th>Rule</th>
                <th>Lift</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($liftResults)): ?>
                <?php foreach ($liftResults as $row): ?>
                    <tr>
                        <td><?= esc($row['rule']) ?></td>
                        <td><?= esc($row['lift']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">Tidak ada data lift ratio.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Top 5 Asosiasi 2 Item</h2>
    <table>
        <thead>
            <tr>
                <th>Rule</th>
                <th>Confidence</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($association2)): ?>
                <?php foreach ($association2 as $row): ?>
                    <tr>
                        <td><?= esc($row['from_item_name']) ?> -> <?= esc($row['to_item_name']) ?></td>                        
                        <td><?= esc($row['confidence_percent']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">Tidak ada asosiasi 2 item.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Top 5 Asosiasi 3 Item</h2>
    <table>
        <thead>
            <tr>
                <th>Rule</th>
                <th>Confidence</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($association3)): ?>
                <?php foreach ($association3 as $row): ?>
                    <tr>
                        <td><?= esc($row['from_item_name']) ?> -> <?= esc($row['to_item_name']) ?></td>                        
                        <td><?= esc($row['confidence_percent']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2">Tidak ada asosiasi 3 item.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
