<?php
// Verbinding met database
$dsn = 'mysql:host=localhost;dbname=gebruikers_db';
$username = 'root'; // Standaard gebruikersnaam
$password = '';     // Leeg wachtwoord


try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Meldingen ophalen
    $query = "SELECT * FROM meldingen";
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
                .container {
            display: flex;
            justify-content: space-between;
        }
        .kolom {
            flex: 1;
            margin: 0 10px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            min-height: 300px;
        }
        .melding {
            background-color: white;
            margin: 10px 0;
            padding: 10px;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            cursor: move;
        }
        .kolommen-container {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        .kolom {
            flex: 1;
            margin: 0 10px;
            background-color: #f0f0f0;
            border-radius: 8px;
            padding: 10px;
        }
        .kolom h2 {
            text-align: center;
            color: #333;
        }
        .melding.dragging {
            opacity: 0.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav {
            background-color: #2E0E4A;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            width: 100%;
            position: fixed;
            top: 0;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            background-color: #ec4a67;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">mborijn/land</div>
            <ul class="nav-links">
                <li><a href="index.php">HOME</a></li>
                <li><a href="informatie.php">INFORMATIE</a></li>
                <li><a href="contact.php">CONTACT</a></li>
                <li><a href="melding_maken.php">MELDING MAKEN</a></li>
            </ul>
            <div class="auth-buttons">
                <span id="welcomeMessage">Welkom!</span>
                <button id="logoutButton">Uitloggen</button>
            </div>
        </nav>
    </header>
    <div class="kolommen-container">
        <div class="kolom" id="actief">
            <h2>Actieve Taken</h2>
            <?php foreach ($meldingen as $melding): ?>
                <?php if ($melding['status'] === 'actief'): ?>
                    <div class="melding" draggable="true" id="melding-<?= $melding['id'] ?>">
                        <h3><?= htmlspecialchars($melding['categorie']) ?></h3>
                        <p><?= htmlspecialchars($melding['beschrijving']) ?></p>
                        <p>Locatie: <?= htmlspecialchars($melding['locatie']) ?> <?= htmlspecialchars($melding['specifieke_locatie']) ?></p>
                        <p>Gemeld door: <?= htmlspecialchars($melding['naam']) ?> (<?= htmlspecialchars($melding['rol']) ?>)</p>
                        <p>Datum: <?= htmlspecialchars($melding['datum']) ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="kolom" id="bezig">
            <h2>Bezig</h2>
            <?php foreach ($meldingen as $melding): ?>
                <?php if ($melding['status'] === 'bezig'): ?>
                    <div class="melding" draggable="true" id="melding-<?= $melding['id'] ?>">
                        <h3><?= htmlspecialchars($melding['categorie']) ?></h3>
                        <p><?= htmlspecialchars($melding['beschrijving']) ?></p>
                        <p>Locatie: <?= htmlspecialchars($melding['locatie']) ?> <?= htmlspecialchars($melding['specifieke_locatie']) ?></p>
                        <p>Gemeld door: <?= htmlspecialchars($melding['naam']) ?> (<?= htmlspecialchars($melding['rol']) ?>)</p>
                        <p>Datum: <?= htmlspecialchars($melding['datum']) ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="kolom" id="klaar">
            <h2>Klaar</h2>
            <?php foreach ($meldingen as $melding): ?>
                <?php if ($melding['status'] === 'klaar'): ?>
                    <div class="melding" draggable="true" id="melding-<?= $melding['id'] ?>">
                        <h3><?= htmlspecialchars($melding['categorie']) ?></h3>
                        <p><?= htmlspecialchars($melding['beschrijving']) ?></p>
                        <p>Locatie: <?= htmlspecialchars($melding['locatie']) ?> <?= htmlspecialchars($melding['specifieke_locatie']) ?></p>
                        <p>Gemeld door: <?= htmlspecialchars($melding['naam']) ?> (<?= htmlspecialchars($melding['rol']) ?>)</p>
                        <p>Datum: <?= htmlspecialchars($melding['datum']) ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        // JavaScript voor drag-and-drop (ongewijzigd)
        document.querySelectorAll('.melding').forEach(melding => {
            melding.addEventListener('dragstart', () => melding.classList.add('dragging'));
            melding.addEventListener('dragend', () => melding.classList.remove('dragging'));
        });
        document.querySelectorAll('.kolom').forEach(kolom => {
            kolom.addEventListener('dragover', e => {
                e.preventDefault();
                const dragging = document.querySelector('.dragging');
                kolom.appendChild(dragging);
            });
        });
        document.getElementById('logoutButton').addEventListener('click', () => {
            localStorage.removeItem('isLoggedIn');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
