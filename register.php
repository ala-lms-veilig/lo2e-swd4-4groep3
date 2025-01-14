<?php
// Start de sessie
session_start();

// Verbinding maken met de database
include('db.php'); // Zorg ervoor dat je de juiste databaseverbinding hebt

// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verkrijg de formuliergegevens
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Controleer of het e-mailadres al bestaat
    $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // E-mailadres bestaat al
        $error_message = "Dit e-mailadres is al geregistreerd. Gebruik een ander e-mailadres.";
    } else {
        // Versleutel het wachtwoord
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Voeg de gebruiker toe aan de database
        $stmt = $conn->prepare("INSERT INTO gebruikers (email, gebruikersnaam, wachtwoord, rol) 
                                VALUES (:email, :username, :password, :role)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Verkrijg de gebruikersgegevens na registratie
        $user_id = $conn->lastInsertId(); // Haal het ID van de nieuw aangemaakte gebruiker op

        // Stel de sessievariabelen in en log de gebruiker in
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userEmail'] = $email;
        $_SESSION['userName'] = $username;
        $_SESSION['userRole'] = $role;
        $_SESSION['userId'] = $user_id; // Voeg het gebruikers-ID toe aan de sessie

        // Succesbericht en doorverwijzing naar de meldingenpagina
        header('Location: melding_maken.php'); // Redirect naar de melding maken pagina
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("image/banner2.jpg");
            background-repeat: no-repeat;  
            background-size: cover;        
            background-position: center; 
        }

        .register-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="email"], input[type="text"], input[type="password"], select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registreren</h2>

        <!-- Formulier voor registratie -->
        <form method="POST">
            <label for="email">E-mailadres:</label>
            <input type="email" name="email" required><br>

            <label for="username">Gebruikersnaam:</label>
            <input type="text" name="username" required><br>

            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" required><br>

            <label for="role">Kies je rol:</label>
            <select name="role" required>
                <option value="docent">Docent</option>
                <option value="leerling">Leerling</option>
                <option value="beveiliger">Beveiliger</option>
                <option value="beheerder">Beheerder</option>
            </select><br>

            <button type="submit">Registreren</button>
        </form>

        <!-- Fout- of succesbericht -->
        <?php if (isset($error_message)) { echo "<p class='message'>$error_message</p>"; } ?>
        <?php if (isset($success_message)) { echo "<p class='message'>$success_message</p>"; } ?>
    </div>
</body>
</html>
