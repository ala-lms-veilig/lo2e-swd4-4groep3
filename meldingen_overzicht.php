<?php
// Verbinding met database
session_start();
$dsn = 'mysql:host=localhost;dbname=gebruikers_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controle of de gebruiker een beheerder is
    $isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'beheerder';

    // Verwijder melding als de beheerder een verzoek stuurt
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && $isAdmin) {
        $deleteId = intval($_POST['delete_id']);
        $deleteQuery = "DELETE FROM meldingen WHERE melding_id = :id";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $deleteStmt->execute();
        header("Location: meldingen_overzicht.php");
        exit;
    }

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
            background-color: #d93450;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        #logoutButton {
            background-color: #f5515f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #logoutButton:hover {
            background-color: #d9404d;
        }

        .melding {
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
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
        }

        button {
            font-size: 1rem;
            color: white;
            background-color: red;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: darkred;
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
        <button id="logoutButton" onclick="location.href='login.php'">Uitloggen</button>
    </header>

    <div class="container">
        <?php if (empty($meldingen)): ?>
            <p>Geen meldingen gevonden.</p>
        <?php else: ?>
            <?php foreach ($meldingen as $melding): ?>
                <div class="melding" data-id="<?= htmlspecialchars($melding['melding_id']) ?>">
                    <h3><?= htmlspecialchars($melding['categorie']) ?></h3>
                    <p><?= htmlspecialchars($melding['beschrijving']) ?></p>
                    <p>Locatie: <?= htmlspecialchars($melding['locatie']) ?> <?= htmlspecialchars($melding['specifieke_locatie'] ?? '') ?></p>
                    <p>Gemeld door: <?= htmlspecialchars($melding['gebruiker_naam']) ?> (<?= htmlspecialchars($melding['gebruiker_rol']) ?>)</p>
                    <p>Datum: <?= htmlspecialchars($melding['datum']) ?></p>
                    <?php if ($isAdmin): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= htmlspecialchars($melding['melding_id']) ?>">
                            <button type="submit">Verwijder</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?= date("Y") ?> Meldingen Overzicht. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
