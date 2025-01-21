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
    $specifiekeLocatie = $_POST['specifiekeLocatie']; // Zorg dat dit veld altijd wordt meegenomen
    $datum = date('Y-m-d H:i:s');

    // Controleer of specifieke locatie ingevuld is, tenzij locatie "aula" is
    if (empty($specifiekeLocatie) && $locatie !== 'aula') {
        echo "<script>alert('Vul een specifieke locatie in.');</script>";
        exit;
    }

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
        /* Algemene stijlen */
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
            margin: 100px auto;
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





body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f0f0f0;
  justify-content: center;
}

.plattegrond {
  display: flex;
  gap: 20px;
  background-color: #e0e0e0;
  padding: 20px;
  border: 2px solid #000;
  justify-content: center;
}

.toren {
  display: flex;
  flex-direction: column-reverse; /* Verdiepingen worden van onder naar boven weergegeven */
  align-items: center;
  justify-content: flex-start;
  width: 100px;
  background-color: #d9d9d9;
  border: 2px solid #000;
  padding: 10px;
  text-align: center;
}

.verdieping {
  width: 80px;
  height: 30px;
  background-color: #4caf50;
  border: 1px solid #000;
  margin-bottom: 3px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.verdieping:hover {
  background-color: #388e3c;
}

.verdieping.aula {
  background-color: #ff9800; /* Aula krijgt een andere kleur */
}

.verdieping.aula:hover {
  background-color: #e65100;
}

/* Pop-up styling */
#mapPopup {
  display: none;
  position: fixed;
  top: 10%;
  left: 10%;
  width: 80%;
  height: 80%;
  background: white;
  border: 2px solid #000;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

#mapOverlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

#mapPopup h3 {
  margin: 0;
  padding: 0;
  font-size: 18px;
}

#mapPopup button {
  background: red;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  font-size: 14px;
  border-radius: 3px;
  transition: background 0.3s ease;
}

#mapPopup button:hover {
  background: darkred;
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
                <option value="locatie1">toren A</option>
                <option value="locatie2">toren B</option>
                <option value="locatie3">toren C</option>
            </select>

            <label for="specifiekeLocatie">Specifieke locatie:</label>
<input type="text" id="specifiekeLocatie" name="specifiekeLocatie" placeholder="Klik op een toren en verdieping" readonly>

<!-- Knop voor interactieve plattegrond -->
<button type="button" id="openMapBtn" onclick="openMapPopup()">Selecteer via plattegrond</button>

<!-- Pop-up container -->
<div id="mapPopup" style="display: none; position: fixed; top: 10%; left: 10%; width: 80%; height: 80%; background: white; border: 2px solid #000; box-shadow: 0 0 10px rgba(0,0,0,0.5); z-index: 1000;">
  <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #2E0E4A; color: white;">
    <h3>Selecteer een specifieke locatie</h3>
    <button onclick="closeMapPopup()" style="background: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Sluiten</button>
  </div>
  <div style="padding: 20px;">
    <div class="plattegrond">
      <!-- Toren A -->
      <div class="toren">
        <h3>Toren A</h3>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 1')">1</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 2')">2</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 3')">3</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 4')">4</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 5')">5</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 6')">6</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 7')">7</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 8')">8</div>
        <div class="verdieping" onclick="selectLocation('Toren A - Verdieping 9')">9</div>
      </div>

      <!-- Toren B -->
      <div class="toren">
        <h3>Toren B</h3>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 1')">1</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 2')">2</div>
        <div class="verdieping aula" onclick="selectLocation('Toren B - Aula')">Aula</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 4')">4</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 5')">5</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 6')">6</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 7')">7</div>
        <div class="verdieping" onclick="selectLocation('Toren B - Verdieping 8')">8</div>
      </div>

      <!-- Toren C -->
      <div class="toren">
        <h3>Toren C</h3>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 1')">1</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 2')">2</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 3')">3</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 4')">4</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 5')">5</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 6')">6</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 7')">7</div>
        <div class="verdieping" onclick="selectLocation('Toren C - Verdieping 8')">8</div>
      </div>
    </div>
  </div>
</div>

<!-- Overlay -->
<div id="mapOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;" onclick="closeMapPopup()"></div>


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

    <script>
  function openMapPopup() {
    document.getElementById('mapPopup').style.display = 'block';
    document.getElementById('mapOverlay').style.display = 'block';
  }

  function closeMapPopup() {
    document.getElementById('mapPopup').style.display = 'none';
    document.getElementById('mapOverlay').style.display = 'none';
  }

  function selectLocation(location) {
    document.getElementById('specifiekeLocatie').value = location;
    closeMapPopup();
  }
</script>

    <footer>
        <p>&copy; 2024 mborijn/land. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
