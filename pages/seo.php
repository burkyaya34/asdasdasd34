<?php
$_seo = $db->prepare("SELECT * FROM seo WHERE id=1");
$_seo->execute();
if ($_seo->rowCount() > 0) {
    $seo = $_seo->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meta_title = htmlspecialchars($_POST['meta_title']);
    $meta_description = htmlspecialchars($_POST['meta_description']);
    $meta_keywords = htmlspecialchars($_POST['meta_keywords']);

    // Veritabanına kaydetme veya dosyaya yazma işlemleri
    // Örnek: Dosyaya kaydetme
    $seo_data =  "meta_title='$meta_title', meta_description='$meta_description', meta_keywords='$meta_keywords'";

    $_seo = $db->prepare("UPDATE seo SET $seo_data WHERE id=1");
    $_seo->execute();
    

    // Geri bildirim
    echo "<script>alert('SEO ayarları başarıyla kaydedildi!'); window.location = 'index.php?page=seo';</script>";
}
?>

<h2>SEO Ayarları</h2>
<p>Bu form üzerinden sitenizin SEO meta bilgilerini düzenleyebilirsiniz.</p>
<form action="" method="POST">
    <div class="mb-3">
        <label for="meta_title" class="form-label">Meta Başlığı</label>
        <input value="<?= $seo['meta_title'] ?>" type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Site başlığını girin" required>
    </div>
    <div class="mb-3">
        <label for="meta_description" class="form-label">Meta Açıklaması</label>
        <textarea class="form-control" id="meta_description" name="meta_description" rows="4" placeholder="Site açıklamasını girin" required><?= $seo['meta_description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="meta_keywords" class="form-label">Meta Anahtar Kelimeler</label>
        <input value="<?= $seo['meta_keywords'] ?>" type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Anahtar kelimeleri virgülle ayırarak girin" required>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Kaydet
        </button>
    </div>
</form>
