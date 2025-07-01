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
            color: #856404;
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
            <p>Berdasarkan hasil analisis, kombinasi terbaik adalah <strong><?= esc($bestRule['from_item_name']) ?> → <?= esc($bestRule['to_item_name']) ?></strong> dengan confidence <strong><?= esc($bestRule['confidence_percent']) ?>%</strong>. Disarankan untuk fokus pada promosi atau pengembangan strategi untuk kombinasi produk ini.</p>
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
                    <?php foreach ($liftResults as $lift): ?>
                        <tr>
                            <td><?= esc($lift['rule']) ?></td>
                            <td><?= esc($lift['lift']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Belum ada data Lift Ratio.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Top 5 Asosiasi 2 Item</h2>
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

        <h2>Top 5 Asosiasi 3 Item</h2>
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
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
    </script>


</body>
</html>
