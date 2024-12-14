<table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Tarih</th>
            <th>IP</th>
            <th>Servis</th>
            <th>Dosya</th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $db->prepare("SELECT orders.*, services2.name FROM orders LEFT JOIN services2 ON orders.service=services2.id");
        $orders->execute();
        if ($orders->rowCount() > 0) {
            foreach ($orders->fetchAll() as $order) {
                ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['username'] ?></td>
                    <td><?= $order['time'] ?></td>
                    <td><?= $order['ip'] ?></td>
                    <td><?= $order['name'] ?></td>
                    <td><a href="/uploads/<?= $order['file'] ?>">Tıkla</a></td>
                    <td><button class="btn btn-primary btn-sm">Düzenle</button></td>
                </tr>
            <?php }
        } ?>
    </tbody>
</table>