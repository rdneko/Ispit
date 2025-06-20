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