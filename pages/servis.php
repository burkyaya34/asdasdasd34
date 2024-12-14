<?php
// Veritabanı bağlantısı
include('../config.php');

// Durum güncelleme işlemi
if (isset($_POST['service_id']) && isset($_POST['new_status'])) {
    $service_id = $_POST['service_id'];
    $new_status = $_POST['new_status'];

    // Durumu güncelleme sorgusu
    $query = $db->prepare("UPDATE services SET is_active = :new_status WHERE id = :service_id");
    $query->bindParam(':new_status', $new_status, PDO::PARAM_INT);
    $query->bindParam(':service_id', $service_id, PDO::PARAM_INT);

    if ($query->execute()) {
        // Durum başarıyla güncellenirse, sayfayı yenile
        header("Location: " . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
        exit;
    } else {
        echo "<script>alert('Durum güncellenemedi.');</script>";
    }
}

// Hizmet düzenleme işlemi
if (isset($_POST['edit_service_id']) && isset($_POST['edit_name']) && isset($_POST['edit_description'])) {
    $service_id = $_POST['edit_service_id'];
    $name = $_POST['edit_name'];
    $description = $_POST['edit_description'];

    // Hizmet bilgilerini güncelleme sorgusu
    $updateQuery = $db->prepare("UPDATE services SET name = :name, description = :description WHERE id = :service_id");
    $updateQuery->bindParam(':name', $name, PDO::PARAM_STR);
    $updateQuery->bindParam(':description', $description, PDO::PARAM_STR);
    $updateQuery->bindParam(':service_id', $service_id, PDO::PARAM_INT);

    if ($updateQuery->execute()) {
        // Hizmet başarıyla güncellenirse, sayfayı yenile
        header("Location: " . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
        exit;
    } else {
        echo "<script>alert('Hizmet güncellenemedi.');</script>";
    }
}

// Yeni hizmet ekleme işlemi (services2 tablosu)
if (isset($_POST['service_name']) && isset($_POST['service_price']) && isset($_POST['category_id'])) {
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];
    $category_id = $_POST['category_id'];

    // Yeni veriyi ekleme sorgusu
    $insertQuery = $db->prepare("INSERT INTO services2 (name, price, service_id) VALUES (:name, :price, :service_id)");
    $insertQuery->bindParam(':name', $service_name, PDO::PARAM_STR);
    $insertQuery->bindParam(':price', $service_price, PDO::PARAM_STR);
    $insertQuery->bindParam(':service_id', $category_id, PDO::PARAM_INT);

    if ($insertQuery->execute()) {
        echo "<script>alert('Hizmet başarıyla eklendi.');</script>";
    } else {
        echo "<script>alert('Hizmet eklenemedi.');</script>";
    }
}

// Veritabanından tüm hizmetleri çekme
$query = $db->prepare("SELECT * FROM services");
$query->execute();
$services = $query->fetchAll(PDO::FETCH_ASSOC);

// Veritabanından services2 tablosundaki tüm hizmetleri çekme
$query2 = $db->prepare("SELECT * FROM services2");
$query2->execute();
$services2 = $query2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmetler</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Hizmetler</h2>
    <p>Bu sayfada hizmetlerinizi düzenleyebilir, durumlarını değiştirebilir ve yeni hizmetler ekleyebilirsiniz.</p>

    <h3>Yeni Hizmet Ekle</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="service_name">Hizmet Adı</label>
            <input type="text" class="form-control" id="service_name" name="service_name" required>
        </div>
        <div class="form-group">
            <label for="service_price">Fiyat</label>
            <input type="number" class="form-control" id="service_price" name="service_price" required>
        </div>
        <div class="form-group">
            <label for="category_id">Kategori</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Kategori Seçin</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?php echo $service['id']; ?>"><?php echo $service['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ekle</button>
    </form>

    <h3 class="mt-5">Hizmetler Listesi</h3>
    <table class="table table-bordered" id="services-table">
        <thead>
            <tr>
                <th>Hizmet Adı</th>
                <th>Fiyat</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services2 as $service): ?>
                <tr>
                    <td><?php echo $service['name']; ?></td>
                    <td><?php echo $service['price']; ?> ₺</td>
                    <td>
                        <?php
                        // Kategori adı çekme
                        $categoryQuery = $db->prepare("SELECT name FROM services WHERE id = :service_id");
                        $categoryQuery->bindParam(':service_id', $service['service_id'], PDO::PARAM_INT);
                        $categoryQuery->execute();
                        $category = $categoryQuery->fetch(PDO::FETCH_ASSOC);
                        echo $category['name'];
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
