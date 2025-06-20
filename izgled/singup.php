<?php
session_start();
include 'config.php'; // Konekcija sa bazom

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // ✅ Šifrovanje lozinke

    // ✅ Ubacivanje novog korisnika u bazu
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, FALSE)");
    if (!$stmt) { 
        echo "<script>alert('Greška pri dodavanju korisnika!'); window.location.href='Kupovina_signup.html';</script>";
        exit();
    }
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        if ($stmt->execute()) {
    echo "✅ Korisnik uspešno dodat: " . $username . " (" . $email . ")";
    exit();
}

        $_SESSION['username'] = $username;
        echo "✅ Korisnik uspešno dodat: " . $username . " (" . $email . ")"; // Privremeno ispisivanje potvrde
        header("Location: Kupovina.html"); // ✅ Sada će se korisnik pravilno preusmeriti
        exit();
    } else {
        echo "<script>alert('Imamo trenutno nekih problema, pokušajte kasnije!'); window.location.href='Kupovina_signup.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



