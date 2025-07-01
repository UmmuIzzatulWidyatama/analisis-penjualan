<!DOCTYPE html>
<html>
<head>
    <title>Upload File Excel Preprocessing</title>
</head>
<body>
    <h2>Upload File Excel Produk</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color: red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" action="<?= base_url('/tipe-produk/uploadPreprocessing') ?>">
        <input type="file" name="file_excel" accept=".xlsx,.xls" required>
        <br><br>
        <button type="submit">Upload dan Preprocessing</button>
    </form>
</body>
</html>
