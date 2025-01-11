<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($password, $gebruiker['wachtwoord'])) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userEmail'] = $gebruiker['email'];
        $_SESSION['userName'] = $gebruiker['gebruikersnaam'];
        $_SESSION['userRole'] = $gebruiker['rol'];
        
        header('Location: melding_maken.php'); // Verwijs naar de homepagina
        exit();
    } else {
        $error_message = "Ongeldig e-mailadres of wachtwoord.";
    }
}
?>

<!-- HTML Formulier voor login -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("image/banner2.jpg");
            background-repeat: no-repeat;  
            background-size: cover;        
            background-position: center; 
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
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

        input[type="email"], input[type="password"] {
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

        p {
            margin-top: 15px;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .register-btn {
            background-color: #008CBA;
            margin-top: 10px;
        }

        .register-btn:hover {
            background-color: #007bb5;
        }

        .back-btn {
            background-color: #e83a4f;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #e83a4f;
        }
    </style>
<body>
    <div class="login-container">
        <h2>Inloggen</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="E-mailadres" required><br>
            <input type="password" name="password" placeholder="Wachtwoord" required><br>
            <button type="submit">Inloggen</button>
        </form>
        <p><a href="change_password.php">Wachtwoord vergeten?</a></p>
        <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
        <button class="register-btn" onclick="window.location.href='register.php'">Registreren</button>
        <button class="back-btn" onclick="window.location.href='index.php'">Terug naar hoofdpagina</button>
    </div>
</body>
</html>
