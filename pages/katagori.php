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

// Veritabanından tüm hizmetleri çekme
$query = $db->prepare("SELECT * FROM services");
$query->execute();
$services = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <p>Bu sayfada hizmetlerinizi düzenleyebilir ve durumlarını değiştirebilirsiniz.</p>

    <table class="table table-bordered" id="services-table">
        <thead>
            <tr>
                <th>Hizmet Adı</th>
                <th>Açıklama</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody id="services-body">
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo $service['name']; ?></td>
                    <td><?php echo $service['description']; ?></td>
                    <td>
                        <!-- Durum güncelleme formu -->
                        <form method="POST" action="">
                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                            <input type="hidden" name="new_status" value="<?php echo $service['is_active'] == 1 ? 0 : 1; ?>">
                            <button type="submit" class="btn <?php echo $service['is_active'] == 1 ? 'btn-success' : 'btn-danger'; ?>">
                                <?php echo $service['is_active'] == 1 ? 'Açık' : 'Kapalı'; ?>
                            </button>
                        </form>
                    </td>
                    <td>
                        <!-- Düzenleme işlemi için buton -->
                        <button class="btn btn-primary" onclick="openEditModal(<?php echo $service['id']; ?>, '<?php echo $service['name']; ?>', '<?php echo $service['description']; ?>')">Düzenle</button>
                    </td>
                </tr>
                
            <?php endforeach; ?>
        </tbody>
    </table>
        <!-- Sayfa butonları -->
        <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination"></ul>
    </nav>
</div>

<!-- Düzenleme Popup (Modal) -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Hizmet Düzenle</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="">
            <input type="hidden" id="edit_service_id" name="edit_service_id">
            <div class="form-group">
                <label for="edit_name">Hizmet Adı</label>
                <input type="text" class="form-control" id="edit_name" name="edit_name" required>
            </div>
            <div class="form-group">
                <label for="edit_description">Açıklama</label>
                <textarea class="form-control" id="edit_description" name="edit_description" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    // PHP tarafından verilmiş olan tüm hizmet verilerini alıyoruz
    const services = <?php echo json_encode($services); ?>;
    const servicesPerPage = 6; // Her sayfada gösterilecek hizmet sayısı
    const totalPages = Math.ceil(services.length / servicesPerPage); // Toplam sayfa sayısı

    // Sayfa numarasını değiştiren fonksiyon
    function paginate(pageNumber) {
        const start = (pageNumber - 1) * servicesPerPage;
        const end = start + servicesPerPage;
        const servicesToShow = services.slice(start, end);

        // Tabloyu temizle
        const tbody = document.getElementById('services-body');
        tbody.innerHTML = '';

        // Gösterilecek hizmetleri tabloya ekle
        servicesToShow.forEach(service => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${service.name}</td>
                <td>${service.description}</td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="service_id" value="${service.id}">
                        <input type="hidden" name="new_status" value="${service.is_active == 1 ? 0 : 1}">
                        <button type="submit" class="btn ${service.is_active == 1 ? 'btn-success' : 'btn-danger'}">
                            ${service.is_active == 1 ? 'Açık' : 'Kapalı'}
                        </button>
                    </form>
                </td>
                <td>
                    <button class="btn btn-primary" onclick="openEditModal(${service.id}, '${service.name}', '${service.description}')">Düzenle</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Sayfa butonlarını oluşturma
    function createPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = ''; // Mevcut sayfa butonlarını temizle

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.classList.add('page-item');
            li.innerHTML = `<a class="page-link" href="#" onclick="paginate(${i})">${i}</a>`;
            pagination.appendChild(li);
        }
    }

    // İlk sayfayı yükle
    window.onload = function() {
        paginate(1);
        createPagination();
    };

    // Modalda düzenleme yapmak için fonksiyon
    function openEditModal(id, name, description) {
        // Modalda ilgili alanları doldur
        document.getElementById('edit_service_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        // Modalı göster
        $('#editModal').modal('show');
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
