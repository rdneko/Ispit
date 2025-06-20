<?php
session_start();
include 'config.php';

// Provera da li je korisnik ulogovan
if (!isset($_SESSION['user_id'])) {
    header("Location: Kupovina.php");
    exit;
}

// Dohvatanje proizvoda iz baze
try {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Greška pri dohvatanju proizvoda: " . $conn->error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solarni Shop</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/shop.css">
</head>
<body>

<nav class="navigacija">
    <ul>
        <li><a href="Sajt.php">Početna</a></li>
        <li><a href="O_nama.php">O nama</a></li>
        <li><a href="Kupovina.php">Kupovina</a></li>
        <li><a href="Kontakt.php">Kontakt</a></li>
        <?php if(isset($_SESSION['user_id'])): ?>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="logout.php">Odjava</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="welcome-section">
    <h1>Dobrodošli u Solarni Shop, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Izaberite proizvod koji vam odgovara</p>
</div>

<div class="container">
    <div class="products-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php 
                // Formiranje putanje za sliku
                $image_path = "../images/" . htmlspecialchars(basename($row['image']));
                ?>
                <div class="product-card">
                    <img src="<?php echo $image_path; ?>" 
                         alt="<?php echo htmlspecialchars($row['name']); ?>" 
                         class="product-image"
                         onerror="this.src='../images/no-image.jpg';this.onerror=null;">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="product-price">Cena: <?php echo number_format($row['price'], 0, ',', '.'); ?> RSD</p>
                        <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="buy-btn-container">
                            <a href="kupovina_proizvoda.php?id=<?php echo $row['id']; ?>" class="buy-btn">Kupi</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-products">
                <p>Trenutno nemamo dostupnih proizvoda. Vratite se kasnije.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $conn->close(); ?>

<script>
// Proširivanje opisa na klik
document.querySelectorAll('.product-description').forEach(desc => {
    desc.addEventListener('click', function() {
        if (this.style.height === '70px' || !this.style.height) {
            this.style.height = 'auto';
            this.style.overflow = 'visible';
        } else {
            this.style.height = '70px';
            this.style.overflow = 'hidden';
        }
    });
});

// Fallback za slike koje ne postoje
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-image').forEach(img => {
        img.onerror = function() {
            this.src = '../images/no-image.jpg';
            this.onerror = null;
        };
    });
});
</script>

</body>
</html>