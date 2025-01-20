<?php
session_start();
include('db.php'); // Zorg ervoor dat db.php de PDO-verbinding gebruikt

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Controleer of gebruikers_id in de sessie beschikbaar is
    if (!isset($_SESSION['gebruikers_id'])) {
        echo "<script>alert('Je moet ingelogd zijn om een melding te maken.');</script>";
        exit;
    }

    // Verkrijg formulierdata
    $gebruikers_id = $_SESSION['gebruikers_id']; // Haal gebruikers_id uit de sessie
    $categorie = $_POST['categorie'];
    $beschrijving = $_POST['beschrijving'];
    $locatie = $_POST['locatie'];
    $specifiekeLocatie = $_POST['specifiekeLocatie'] ?? null; // Optioneel veld
    $datum = date('Y-m-d H:i:s');

    // Prepared statement om SQL-injecties te voorkomen (met PDO)
    $sql = "INSERT INTO meldingen (categorie, beschrijving, locatie, specifieke_locatie, datum, gebruikers_id, status) 
        VALUES (:categorie, :beschrijving, :locatie, :specifiekeLocatie, :datum, :gebruikers_id, 'actief')";

    
    // Bereid de statement voor
    $stmt = $conn->prepare($sql);
    
    // Bind de parameters
    $stmt->bindParam(':categorie', $categorie);
    $stmt->bindParam(':beschrijving', $beschrijving);
    $stmt->bindParam(':locatie', $locatie);
    $stmt->bindParam(':specifiekeLocatie', $specifiekeLocatie);
    $stmt->bindParam(':datum', $datum);
    $stmt->bindParam(':gebruikers_id', $gebruikers_id);

    // Voer de query uit
    if ($stmt->execute()) {
        echo "<script>alert('Melding succesvol opgeslagen!');</script>";
    } else {
        echo "<script>alert('Fout bij het opslaan van de melding.');</script>";
    }
}

// Haal naam en rol op uit de sessie
$naam = $_SESSION['userName'] ?? 'Onbekend'; // Gebruik een standaardwaarde als naam ontbreekt
$rol = $_SESSION['userRole'] ?? 'Onbekend'; // Gebruik een standaardwaarde als rol ontbreekt
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melding Maken</title>
    <script defer src="hamburger.js"></script>
    <style>
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

        .cta {
            background-color: #ff8b00;
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 10px; 
            color: white;
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

        #beheerderLink {
            display: none; 
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #beheerderLink:hover {
            background-color: #45a049;
        }

        main {
            background-color: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        select, input[type="text"], textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        input[type="submit"] {
            background-color: #f5515f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #d9404d;
        }

        footer {
            background-color: #2E0E4A;
            padding: 20px;
            text-align: center;
            color: white;
        }

        footer p {
            color: white;
            font-size: 18px;
        }

        #specifiekeLocatie {
            display: none;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            display: none; 
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">mborijn/land</div>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="informatie.html">INFORMATIE</a></li>
                <li><a href="contact.html">CONTACT</a></li>
                <li><a href="melding_maken.php">MELDING MAKEN</a></li>
                <li><a href="meldingen_overzicht.php">OVERZICHT</a></li>
            </ul>
            <div class="auth-buttons">
                <span id="welcomeMessage">Welkom, <?php echo htmlspecialchars($naam); ?>!</span>
                <button id="logoutButton" onclick="location.href='login.php'">Uitloggen</button>
                <button id="beheerderLink" onclick="location.href='beheerder-dashboard.html'">Beheerder Dashboard</button>
            </div>
        </nav>
    </header>

    <main>
        <h1>Maak een Melding</h1>
        <form id="meldingForm" method="POST" action="melding_maken.php">
            <label for="categorie">Type Melding:</label>
            <select name="categorie" id="categorie" required>
                <option value="">-- Maak een keuze --</option>
                <option value="geweld">Geweld</option>
                <option value="geluidsoverlast">Geluidsoverlast</option>
                <option value="diefstal">Diefstal</option>
                <option value="technische storingen">Technische Storing</option>
                <option value="inbraak">Inbraak</option>
                <option value="pesten">Pesten</option>
                <option value="overige">Overige</option>
            </select>

            <label for="locatie">Op welke locatie heeft dit plaatsgevonden?</label>
            <select name="locatie" id="locatie" required>
                <option value="">-- Maak een keuze --</option>
                <option value="aula">Aula</option>
                <option value="locatie1">Locatie 1</option>
                <option value="locatie2">Locatie 2</option>
                <option value="locatie3">Locatie 3</option>
            </select>

            <textarea id="specifiekeLocatie" name="specifiekeLocatie" placeholder="Specifieke locatie (bijv. bij de ingang van de aula)"></textarea>

            <label for="beschrijving">Beschrijving incident:</label>
            <textarea name="beschrijving" id="beschrijving" rows="5" required placeholder="Beschrijf het incident zo compleet mogelijk..."></textarea>

            <h2>Persoonlijke Gegevens</h2>

            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" required readonly value="<?php echo htmlspecialchars($naam); ?>">

            <label for="rol">Rol:</label>
            <input type="text" id="rol" name="rol" required readonly value="<?php echo htmlspecialchars($rol); ?>">

            <div class="button-group">
                <input type="submit" value="Verzenden">
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 mborijn/land. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
