<?php
require __DIR__ . '/vendor/autoload.php';

use App\Clothing;
use App\Electronic;

require __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// connexion à la base
$mysqli = db_connect();
if ($mysqli === null) {
    echo "Erreur : impossible de se connecter à la base. Vérifiez 'config/db.php'.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = (int)($_POST['price'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);

    if ($name !== '') {
        $stmt = $mysqli->prepare('INSERT INTO product (name,price,quantity) VALUES (?,?,?)');
        if ($stmt) {
            $stmt->bind_param('sii', $name, $price, $quantity);
            $stmt->execute();
            $stmt->close();
            $message = "Produit ajouté.";
            // stocke le message en flash et redirige (PRG) pour éviter la double soumission
            $_SESSION['flash'] = $message;
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $message = "Erreur préparation requête : " . $mysqli->error;
        }
    } else {
        $message = "Le nom du produit est requis.";
    }
}

// lecture des produits
$res = $mysqli->query('SELECT * FROM product ORDER BY id DESC');
$products = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $products[] = $row;
    }
}

// récupérer message flash s'il existe
if (isset($_SESSION['flash'])) {
    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

// affichage HTML simple
?>
<!DOCTYPE html>
<html>

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Job15 - Produits</title>
    <?php
    $cssFile = __DIR__ . '/assets/style.css';
    if (file_exists($cssFile) && ($cssContent = @file_get_contents($cssFile)) !== false) {
        echo '<style>' . $cssContent . '</style>';
    }
    ?>
</head>

<body>
    <div class="container">
        <h1>Produits</h1>
        <?php if (!empty($message)): ?><p class="muted"><?php echo htmlspecialchars($message ?? '', ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>

        <form method="post">

            <div class="form-row">
                <label>Nom: <input name="name" required></label>
            </div>
            <div class="form-row">
                <label>Prix: <input name="price" type="number" value="0"></label>
            </div>
            <div class="form-row">
                <label>Quantité: <input name="quantity" type="number" value="0"></label>
            </div>
            <div class="form-row">
            </div>
            <div class="form-row">
                <button type="submit">Ajouter</button>
            </div>
        </form>

        <h2>Liste</h2>
        <div class="grid">
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <strong><?php echo htmlspecialchars($p['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                    <div class="muted">Prix: <?php echo (int)$p['price']; ?></div>
                    <div class="muted">Quantité: <?php echo (int)($p['quantity'] ?? 0); ?></div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>