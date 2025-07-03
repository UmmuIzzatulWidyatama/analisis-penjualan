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
        .summary-box, .insight-box {
        margin-top: 20px;
        padding: 15px;
        background-color: #e9f7ef;
        border-left: 5px solid #28a745;
        border-radius: 5px;
        }
        .insight-box {
            background-color: #fff3cd;
            border-left-color: #ffc107;
        }

        .tab-container {
            margin-top: 20px;
        }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .tab-buttons button {
            padding: 8px 16px;
            border: none;
            background-color: #e0e0e0;
            cursor: pointer;
            border-radius: 4px;
        }
        .tab-buttons button.active {
            background-color: #007bff;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="<?= base_url('rule') ?>">Rule</a>
            <a href="<?= base_url('tipe-produk') ?>">Produk</a>
            <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            <a href="<?= base_url('analisis-data') ?>" class="active">Analisis Data</a>
        </div>
        <a href="<?= site_url('/logout') ?>" class="logout">Logout</a>
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
                <div class="progress-circle ">4</div>
                Itemset 3
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle">5</div>
                Asosiasi
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle ">6</div>
                Lift Ratio
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="progress-circle active">7</div>
                Kesimpulan
            </div>
        </div>

        <h1>Kesimpulan Analisis</h1>

        <p><strong>Deskripsi Analisis:</strong> <?= esc($analisisData['description']) ?></p>

        <div class="summary-box">
            <p><strong>Periode:</strong> <?= esc($analisisData['start_date']) ?> - <?= esc($analisisData['end_date']) ?></p>
            <p><strong>Total Transaksi:</strong> <?= esc($analisisData['transaction_count']) ?></p>
            <p><strong>Minimum Support:</strong> <?= esc($analisisData['minimum_support']) ?>%</p>
            <p><strong>Minimum Confidence:</strong> <?= esc($analisisData['minimum_confidence']) ?>%</p>
        </div>

        <div class="insight-box">
                <?php
                // Ringkasan itemset 1
                if (count($topItemset1) > 0) {
                    $itemNames = array_column($topItemset1, 'item_name');
                    echo "<p><strong>Itemset 1:</strong> Produk-produk seperti <em>{$itemNames[0]}</em>";
                    if (isset($itemNames[1])) echo ", <em>{$itemNames[1]}</em>";
                    if (isset($itemNames[2])) echo ", dan <em>{$itemNames[2]}</em>";
                    echo " merupakan item individual yang paling sering muncul dalam transaksi.</p>";
                }

                // Ringkasan itemset 2
                if (!empty($topItemset2)) {
                    $i2 = $topItemset2[0];
                    echo "<p><strong>Itemset 2:</strong> Kombinasi <em>{$i2['produk_1']}</em> dan <em>{$i2['produk_2']}</em> memiliki support tertinggi ({$i2['support_percent']}%).</p>";
                }

                // Ringkasan itemset 3
                if (!empty($topItemset3)) {
                    $i3 = $topItemset3[0];
                    echo "<p><strong>Itemset 3:</strong> Kombinasi <em>{$i3['produk_1']}</em>, <em>{$i3['produk_2']}</em>, dan <em>{$i3['produk_3']}</em> muncul paling sering ({$i3['support_percent']}%).</p>";
                }

                // Ringkasan aturan asosiasi
                if (!empty($topRules2)) {
                    $r = $topRules2[0];
                    echo "<p><strong>Association Rule:</strong> Aturan <em>{$r['from_item_name']}</em> → <em>{$r['to_item_name']}</em> memiliki confidence tertinggi sebesar {$r['confidence_percent']}%.</p>";
                }

                // Ringkasan lift ratio
                if (!empty($liftResults)) {
                    $l = $liftResults[0];
                    echo "<p><strong>Lift Ratio:</strong> Aturan <em>{$l['rule']}</em> memiliki nilai lift tertinggi sebesar <strong>{$l['lift']}</strong>.</p>";
                }
                ?>

                <hr>
                <p class="mb-0"><strong>Insight Strategis:</strong> Hasil analisis menunjukkan bahwa produk dengan frekuensi tinggi dan asosiasi kuat layak dijadikan target promosi atau penempatan bersama untuk meningkatkan penjualan silang.</p>

        </div>

        <h2>Top 5 Frequent Itemset 1</h2>
        <div class="tab-container" id="tab-itemset1">
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-itemset1">Grafik</button>
                <button class="tab-link" data-tab="table-itemset1">Table</button>
            </div>

            <div id="chart-itemset1" class="tab-content active">
                <canvas id="itemset1Chart" width="400" height="200"></canvas>
            </div>

            <div id="table-itemset1" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Support (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($topItemset1, 0, 5) as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($item['item_name']) ?></td>
                                <td><?= esc($item['support_percent']) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Top 5 Frequent Itemset 2</h2>
        <div class="tab-container" id="tab-itemset2">
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-itemset2">Grafik</button>
                <button class="tab-link" data-tab="table-itemset2">Table</button>
            </div>

            <div id="chart-itemset2" class="tab-content active">
                <canvas id="itemset2Chart" width="400" height="200"></canvas>
            </div>

            <div id="table-itemset2" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kombinasi Produk</th>
                            <th>Support (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($topItemset2, 0, 5) as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $item['produk_1'] ?> & <?= $item['produk_2'] ?></td>
                                <td><?= $item['support_percent'] ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Top 5 Frequent Itemset 3</h2>
        <div class="tab-container" id="tab-itemset3">
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-itemset3">Grafik</button>
                <button class="tab-link" data-tab="table-itemset3">Table</button>
            </div>

            <div id="chart-itemset3" class="tab-content active">
                <canvas id="itemset3Chart" width="400" height="200"></canvas>
            </div>

            <div id="table-itemset3" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kombinasi Produk</th>
                            <th>Support (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($topItemset3, 0, 5) as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($row['produk_1']) ?>, <?= esc($row['produk_2']) ?> & <?= esc($row['produk_3']) ?></td>
                                <td><?= esc($row['support_percent']) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Top 5 Asosiasi 2-Itemset</h2>
        <div class="tab-container" id="tab-2item"> 
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-2item">Grafik</button>
                <button class="tab-link" data-tab="table-2item">Table</button>
            </div>

            <div id="chart-2item" class="tab-content active">
                <canvas id="confidenceChart2" width="400" height="200"></canvas>
            </div>

            <div id="table-2item" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>Rule</th>
                            <th>Confidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topRules2 as $rule): ?>
                            <tr>
                                <td><?= esc($rule['from_item_name']) ?> → <?= esc($rule['to_item_name']) ?></td>
                                <td><?= esc($rule['confidence_percent']) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Top 5 Asosiasi 3-Itemset</h2>
        <div class="tab-container" id="tab-3item">
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-3item">Grafik</button>
                <button class="tab-link" data-tab="table-3item">Table</button>
            </div>

            <div id="chart-3item" class="tab-content active">
                <canvas id="confidenceChart3" width="400" height="200"></canvas>
            </div>
            <div id="table-3item" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>Rule</th>
                            <th>Confidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topRules3 as $rule): ?>
                            <tr>
                                <td><?= esc($rule['from_item_name']) ?> → <?= esc($rule['to_item_name']) ?></td>
                                <td><?= esc($rule['confidence_percent']) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Lift Ratio</h2>
        <div class="tab-container" id="tab-lift">
            <div class="tab-buttons">
                <button class="tab-link active" data-tab="chart-lift">Grafik</button>
                <button class="tab-link" data-tab="table-lift">Table</button>
            </div>

            <div id="chart-lift" class="tab-content active">
                <canvas id="liftChart" width="400" height="200"></canvas>
            </div>

            <div id="table-lift" class="tab-content">
                <table>
                    <thead>
                        <tr>
                            <th>Rule</th>
                            <th>Lift</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($liftResults)): ?>
                            <?php foreach ($liftResults as $lift): ?>
                                <tr>
                                    <td><?= esc($lift['rule']) ?></td>
                                    <td><?= number_format($lift['lift'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2">Belum ada data Lift Ratio.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= base_url('analisis-data') ?>" class="btn btn-secondary">Kembali ke halaman list</a>
        </div>
    </div>

    <script>
        // Per Tab Container Handler
        document.querySelectorAll('.tab-container').forEach(container => {
            const tabLinks = container.querySelectorAll('.tab-link');
            const tabContents = container.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', () => {
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    link.classList.add('active');
                    container.querySelector('#' + link.dataset.tab).classList.add('active');
                });
            });
        });

        // Chart.js untuk Itemset 1
        const ctxItemset1 = document.getElementById('itemset1Chart').getContext('2d');
        const labelsItemset1 = <?= json_encode(array_slice(array_map(fn($item) => $item['item_name'], $topItemset1), 0, 5)) ?>;
        const dataItemset1 = <?= json_encode(array_slice(array_map(fn($item) => $item['support_percent'], $topItemset1), 0, 5)) ?>;

        new Chart(ctxItemset1, {
            type: 'bar',
            data: {
                labels: labelsItemset1,
                datasets: [{
                    label: 'Support (%)',
                    data: dataItemset1,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Chart.js untuk Itemset 2
        const ctxItemset2 = document.getElementById('itemset2Chart').getContext('2d');
        const labelsItemset2 = <?= json_encode(array_slice(array_map(fn($row) => $row['produk_1'] . ' & ' . $row['produk_2'], $topItemset2), 0, 5)) ?>;
        const dataItemset2 = <?= json_encode(array_slice(array_map(fn($row) => $row['support_percent'], $topItemset2), 0, 5)) ?>;

        new Chart(ctxItemset2, {
            type: 'bar',
            data: {
                labels: labelsItemset2,
                datasets: [{
                    label: 'Support (%)',
                    data: dataItemset2,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Chart.js untuk Itemset 3
        const ctxItemset3 = document.getElementById('itemset3Chart').getContext('2d');
        const labelsItemset3 = <?= json_encode(array_slice(array_map(fn($row) => $row['produk_1'] . ', ' . $row['produk_2'] . ' & ' . $row['produk_3'], $topItemset3), 0, 5)) ?>;
        const dataItemset3 = <?= json_encode(array_slice(array_map(fn($row) => $row['support_percent'], $topItemset3), 0, 5)) ?>;
        
        new Chart(ctxItemset3, {
            type: 'bar',
            data: {
                labels: labelsItemset3,
                datasets: [{
                    label: 'Support (%)',
                    data: dataItemset3,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Chart.js untuk Asosiasi 2 Item
        const ctx2 = document.getElementById('confidenceChart2').getContext('2d');
        const labels2 = <?= json_encode(array_map(function($r) {
            return $r['from_item_name'] . ' → ' . $r['to_item_name'];
        }, $topRules2)) ?>;
        const data2 = <?= json_encode(array_map(function($r) {
            return $r['confidence_percent'];
        }, $topRules2)) ?>;

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: 'Confidence (%)',
                    data: data2,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Chart.js untuk Asosiasi 3 Item
        const ctx3 = document.getElementById('confidenceChart3').getContext('2d');

        const labels3 = <?= json_encode(array_map(function($r) {
            return $r['from_item_name'] . ' → ' . $r['to_item_name'];
        }, $topRules3)) ?>;

        const data3 = <?= json_encode(array_map(function($r) {
            return $r['confidence_percent'];
        }, $topRules3)) ?>;

        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: labels3,
                datasets: [{
                    label: 'Confidence (%)',
                    data: data3,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Chart.js Lift Ratio
        const ctxLift = document.getElementById('liftChart')?.getContext('2d');
        const labelsLift = <?= json_encode(array_map(fn($r) => $r['rule'], $liftResults)) ?>;
        const dataLift = <?= json_encode(array_map(fn($r) => floatval($r['lift']), $liftResults)) ?>;

        if (ctxLift && labelsLift.length > 0 && dataLift.length > 0) {
            new Chart(ctxLift, {
                type: 'bar',
                data: {
                    labels: labelsLift,
                    datasets: [{
                        label: 'Lift Ratio',
                        data: dataLift,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } else {
            console.warn("Chart Lift Ratio tidak dibuat: Tidak ada data atau canvas tidak ditemukan.");
        }

    </script>


</body>
</html>
