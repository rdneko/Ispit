<?php

include 'config.php';

if ($conn) {
    echo "Uspešno ste se povezali sa MySQL bazom podataka!<br>";

    $username_to_test = "admin"; // Promenite ovo na korisničko ime administratora

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE username = ?");

    if ($stmt) {
        echo "Upit za dohvat korisnika je uspešno pripremljen.<br>";
        $stmt->bind_param("s", $username_to_test);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Korisnik sa korisničkim imenom '$username_to_test' je pronađen.<br>";
            $stmt->bind_result($id, $hashed_password, $is_admin);
            $stmt->fetch();
            echo "ID: " . htmlspecialchars($id) . "<br>";
            echo "Hashed Password: " . htmlspecialchars($hashed_password) . "<br>";
            echo "Is Admin: " . htmlspecialchars($is_admin) . "<br>";
            if ($is_admin == 1) {
                echo "Ovaj korisnik je administrator.<br>";
            } else {
                echo "Ovaj korisnik nije administrator.<br>";
            }
        } else {
            echo "Korisnik sa korisničkim imenom '$username_to_test' nije pronađen.<br>";
        }
        $stmt->close();
    } else {
        echo "Došlo je do greške prilikom pripreme upita za dohvat korisnika: " . $conn->error . "<br>";
    }

    $conn->close();
} else {
    echo "Došlo je do greške prilikom povezivanja sa MySQL bazom podataka. Proverite vaš config.php fajl.<br>";
}

?>