<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Site de Vente et d'Achat</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #333; color: white; padding: 15px; text-align: center; }
        nav ul { list-style: none; padding: 0; text-align: center; }
        nav ul li { display: inline; margin: 0 15px; }
        nav ul li a { color: white; text-decoration: none; }
        .container { width: 80%; margin: auto; padding: 20px; }
        .products { display: flex; flex-wrap: wrap; gap: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; width: 30%; text-align: center; }
        footer { background: #333; color: white; text-align: center; padding: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur notre site de vente et d'achat</h1>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Achat</a></li>
                <li><a href="#">Vendre un produit</a></li>
                <li><a href="statistiques.php">Statistiques</a></li>
                <li><a href="login.php">Se connecter</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Nos Derniers Produits</h2>
        <div class="products">
            <?php
            $produits = [
                ["nom" => "Smartphone", "prix" => "299€"],
                ["nom" => "Ordinateur Portable", "prix" => "799€"],
                ["nom" => "Casque Audio", "prix" => "99€"]
            ];
            foreach ($produits as $produit) {
                echo "<div class='product'>";
                echo "<h3>{$produit['nom']}</h3>";
                echo "<p>Prix: {$produit['prix']}</p>";
                echo "<button>Acheter</button>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Site de Vente et d'Achat. Tous droits réservés.</p>
    </footer>
</body>
</html>
