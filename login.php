<?php
header('Location: /admin/');
return;

require_once '../config.php';
require_once '../functions.php';

if (!empty($_SESSION['user']))

  if ($data = @$_POST) {
    $account = $db->prepare("SELECT * FROM accounts WHERE email=?");
    $account->execute(['email' => $data['email']]);

    if (!empty($account) && password_verify($data['password'], $account['password'])) {
      $_SESSION['account_id'] = $account['id'];
    }
  }


try {
  $stmt = $db->prepare("SELECT services.name as serviceName, services2.*,
        CASE 
            WHEN services.name = 'Instagram' THEN '#E1306C'
            WHEN services.name = 'TikTok' THEN '#000000'
            WHEN services.name = 'Twitter' THEN '#1DA1F2'
            WHEN services.name = 'YouTube' THEN '#FF0000'
            WHEN services.name = 'Facebook' THEN '#4267B2'
            WHEN services.name = 'Spotify' THEN '#1DB954'
            WHEN services.name = 'Telegram' THEN '#0088cc'
            WHEN services.name = 'LinkedIn' THEN '#0077B5'
            WHEN services.name = 'Kick' THEN '#32CD32'
            WHEN services.name = 'Twitch' THEN '#9146FF'
            WHEN services.name = 'Trovo' THEN '#0EAB89'
            WHEN services.name = 'Google' THEN '#4285F4'
            WHEN services.name = 'App Store' THEN '#007AFF'
            WHEN services.name = 'SEO' THEN '#FFD700'
            WHEN services.name = 'DLive' THEN '#FFCC00'
            WHEN services.name = 'Nonolive' THEN '#FF4500'
            WHEN services.name = 'Tumblr' THEN '#36465D'
            WHEN services.name = 'SoundCloud' THEN '#FF8800'
            WHEN services.name = 'Reddit' THEN '#FF4500'
            WHEN services.name = 'Pinterest' THEN '#E60023'
            WHEN services.name = 'Discord' THEN '#5865F2'
            WHEN services.name = 'Snapchat' THEN '#ffc188'
            WHEN services.name = 'PUBG' THEN '#FFC107'
            ELSE '#6c757d'
        END as color FROM services2
        LEFT JOIN services ON services.id=services2.service_id
        WHERE services2.id = :id");
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  $service = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($service) {
  } else {
    echo 'Servis bulunamadı!';
  }
} catch (PDOException $e) {
  echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Giriş Yap</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f8f9fa;
      margin: 0;
    }

    .card {
      width: 100%;
      max-width: 400px;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-login {
      display: flex;
      justify-content: flex-end;
    }
  </style>
</head>

<body>
  <div class="card">
    <h3 class="text-center mb-4">Giriş Yap</h3>
    <form>
      <div class="mb-3">
        <label for="email" class="form-label">E-posta Adresi</label>
        <input name="email" type="email" class="form-control" id="email" placeholder="E-postanızı girin">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Şifrenizi girin">
      </div>
      <div class="btn-login">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-sign-in-alt"></i> Giriş Yap
        </button>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>