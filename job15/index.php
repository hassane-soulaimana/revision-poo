<?php
require __DIR__ . '/vendor/autoload.php';

use App\Clothing;
use App\Electronic;

require __DIR__ . '/../config/db.php';

// connexion à la base
$mysqli = db_connect();
if ($mysqli === null) {
    echo "Erreur : impossible de se connecter à la base. Vérifiez 'config/db.php'.";
    exit;
}

// traitement du formulaire d'ajout (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'Product';
    $name = $_POST['name'] ?? '';
    $price = (int)($_POST['price'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $meta = trim($_POST['meta'] ?? '');

    if ($name !== '') {
        $stmt = $mysqli->prepare('INSERT INTO product (type,name,price,quantity,meta) VALUES (?,?,?,?,?)');
        if ($stmt) {
            $stmt->bind_param('ssiss', $type, $name, $price, $quantity, $meta);
            $stmt->execute();
            $stmt->close();
            $message = "Produit ajouté.";
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
                <label>Type: <input name="type" value="Product"></label>
            </div>
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
                <label>Meta (texte ou JSON): <input name="meta"></label>
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
                    <div class="muted"><?php echo htmlspecialchars($p['type'] ?? '', ENT_QUOTES, 'UTF-8'); ?> — Prix: <?php echo (int)$p['price']; ?></div>
                    <div class="muted">Quantité: <?php echo (int)($p['quantity'] ?? 0); ?></div>
                    <?php if (!empty($p['meta'])): ?><div class="muted">Meta: <?php echo htmlspecialchars($p['meta'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>