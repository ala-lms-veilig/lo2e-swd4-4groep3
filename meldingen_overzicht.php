<?php
// Verbinding met database
$dsn = 'mysql:host=localhost;dbname=gebruikers_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Meldingen ophalen met de juiste relatie
    $query = "SELECT meldingen.*, gebruikers.gebruikersnaam AS gebruiker_naam, gebruikers.rol AS gebruiker_rol 
              FROM meldingen 
              LEFT JOIN gebruikers ON meldingen.gebruikers_id = gebruikers.gebruikers_id";
    $stmt = $pdo->query($query);
    $meldingen = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Databasefout: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meldingen Overzicht</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #2E0E4A;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        header a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            background-color: #ec4a67;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        header a:hover {
            background-color: #ec4a67;;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .melding {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .melding:hover {
            background-color: #f0f8ff;
        }

        .melding:last-child {
            border-bottom: none;
        }

        .melding h3 {
            margin: 0;
            color: #007BFF;
            font-size: 1.5rem;
        }

        .melding p {
            margin: 5px 0;
            color: #555;
            display: none; /* Verborgen tot aangeklikt */
        }

        .melding.active p {
            display: block; /* Toon details als actief */
        }

        footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px 0;
            background-color: #f4f4f9;
            color: #777;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Meldingen Overzicht</h1>
        <a href="index.html">Home</a>
    </header>

    <div class="container">
        <?php if (empty($meldingen)): ?>
            <p>Geen meldingen gevonden.</p>
        <?php else: ?>
            <?php foreach ($meldingen as $melding): ?>
                <div class="melding" data-id="<?= htmlspecialchars($melding['id'] ?? 'Onbekend') ?>">
                    <h3><?= htmlspecialchars($melding['categorie'] ?? 'Onbekend') ?></h3>
                    <p><?= htmlspecialchars($melding['beschrijving'] ?? 'Geen beschrijving beschikbaar') ?></p>
                    <p>Locatie: <?= htmlspecialchars($melding['locatie'] ?? 'Onbekend') ?>
                        <?= htmlspecialchars($melding['specifieke_locatie'] ?? '') ?></p>
                    <p>Gemeld door: 
                        <?= htmlspecialchars($melding['gebruiker_naam'] ?? 'Onbekend') ?> 
                        (<?= htmlspecialchars($melding['gebruiker_rol'] ?? 'Onbekend') ?>)
                    </p>
                    <p>Datum: <?= htmlspecialchars($melding['datum'] ?? 'Onbekend') ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date("Y") ?> Meldingen Overzicht. Alle rechten voorbehouden.</p>
    </footer>

    <script>
        document.querySelectorAll('.melding').forEach(melding => {
            melding.addEventListener('click', () => {
                melding.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
