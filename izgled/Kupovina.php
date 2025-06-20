<?php
session_start();
include 'config.php';

// Inicijalizacija varijable za poruku o grešci
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username)) {
        $login_error = "Molimo vas da unesete korisničko ime.";
    } elseif (empty($password)) {
        $login_error = "Molimo vas da unesete lozinku.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hashed_password, $is_admin);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;

                if ($is_admin == 1) {
                    $_SESSION['is_admin'] = true;
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: shop.php");
                    exit();
                }
            } else {
                $login_error = "Neispravna lozinka!";
            }
        } else {
            $login_error = "Neispravno korisničko ime!";
        }

        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Solarni Paneli - Kupovina</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/loginsignup.css">
</head>
<body>
    <h1>Solarni Paneli</h1>
    <nav class="navigacija">
        <?php include 'navigacija.php'; ?>
    </nav>

    <div class="pozadina">
        <div class="login">
            <h2>Login</h2>
            <form method="POST">
                <div class="kocka">
                    <input type="text" name="username" required>
                    <div class="sredjivanje">Unesi korisničko ime</div>
                </div>
                <div class="kocka">
                    <input type="password" name="password" required>
                    <div class="sredjivanje">Unesi lozinku</div>
                </div>
                <button type="submit">Login</button>
            </form>
            <a class="signuplink" href="Kupovina_singup.php">Sign Up</a>

            <?php if (!empty($login_error)): ?>
                <p class="error-message"><?php echo htmlspecialchars($login_error); ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message">
        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
    <a href="Kupovina_signup.php">Registrujte se</a>
<?php endif; ?>
</body>
</html>