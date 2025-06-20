<?php
session_start();
include 'config.php';

$show_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validacija
    $errors = [];
    if (empty($username)) $errors[] = "Korisničko ime je obavezno";
    if (empty($email)) $errors[] = "Email adresa je obavezna";
    if (empty($password)) $errors[] = "Lozinka je obavezna";
    if ($password !== $confirm_password) $errors[] = "Lozinke se ne poklapaju";
    if (strlen($password) < 8) $errors[] = "Lozinka mora imati najmanje 8 karaktera";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Neispravna email adresa";

    if (empty($errors)) {
        try {
            // Provera da li korisnik već postoji
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $errors[] = "Korisničko ime ili email već postoje";
            } else {
                // Hash lozinke
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Unos u bazu (is_admin se automatski postavlja na 0)
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                
                if ($stmt->execute()) {
                    $show_success = true;
                } else {
                    $errors[] = "Greška pri registraciji: " . $conn->error;
                }
            }
        } catch (Exception $e) {
            $errors[] = "Došlo je do greške: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        header("Location: Kupovina_signup.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Solarni Paneli</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/loginsignup.css">
    <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .error-message {
            color: red;
            margin: 10px 0;
            font-size: 14px;
        }
        .error-box {
            border: 1px solid red;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            background-color: #ffeeee;
        }
    </style>
</head>
<body>
    <h1>Solarni Paneli</h1>
    <nav class="navigacija">
        <?php include 'navigacija.php'; ?>
    </nav>

    <div class="pozadina">
        <div class="signup">
            <h2>Registracija</h2>
            
            <?php if (isset($_SESSION['signup_errors'])): ?>
                <div class="error-box">
                    <?php foreach ($_SESSION['signup_errors'] as $error): ?>
                        <div class="error-message"><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['signup_errors']); ?>
                </div>
            <?php endif; ?>
            
            <form id="singupforma" method="POST" action="Kupovina_singup.php">
                <div class="kocka">
                    <input type="text" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    <div class="sredjivanje">Unesite korisničko ime</div>
                </div>
                <div class="kocka">
    <input type="email" name="email" id="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" require>
    <div class="sredjivanje">Unesite email adresu</div>
    <div id="erroremail" style="color: red; display: none;">Unesite ispravnu email adresu.</div>
                    </div>
                <div class="kocka">
    <input type="password" name="password" id="password" required minlength="8">
    <div class="sredjivanje">Unesi lozinku (min 8 karaktera)

    </div>
                   
                 <script src="../skripta/errorporuka.js"></script> 
      </div>
                <div class="kocka">
                    <input type="password" name="confirm_password" id="confirm_password" required minlength="8">
                    <div class="sredjivanje">Potvrdite lozinku</div>
                </div>
                <button type="submit">Registruj se</button>
            </form>
            <a class="loginlink" href="Kupovina.php">Prijava</a>
        </div>
    </div>
    <div id="erroremail" style="color: red; display: none;">Unesite ispravnu email adresu.</div>
    <div id="errorpass" class="error-message" style="color: red; display: none;">Unesite ispravnu lozinku.</div>
    <div id="errorconfirm" class="error-message" style="color: red; display: none;">Potvrdite ispravnu lozinku</div>



    <?php if ($show_success): ?>
    <div class="popup-overlay">
        <div class="popup-content">
            <h3>Uspešna registracija!</h3>
            <p>Hvala što ste se registrovali. Sada možete da se prijavite.</p>
            <button onclick="window.location.href='Kupovina.php'">Nastavi na Kupovinu</button>
        </div>
    </div>
    <script>
        // Automatski redirekt nakon 5 sekundi
        setTimeout(function() {
            window.location.href = "Kupovina.php";
        }, 5000);
    </script>

    <?php endif; ?>

    

</body>


</html>